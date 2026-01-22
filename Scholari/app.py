from flask import Flask, render_template, request, redirect, url_for, flash, session
import sqlite3
import hashlib
import random
import smtplib
from email.message import EmailMessage

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Change this in production!
DB_PATH = 'scholari_db.db'

# Helper Functions
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

def get_user_by_email(email):
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    cursor.execute("SELECT id, email, password, password_changed FROM users WHERE email = ?", (email,))
    user = cursor.fetchone()
    conn.close()
    return user

def update_password(user_id, new_password):
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    hashed = hash_password(new_password)
    cursor.execute("UPDATE users SET password = ?, password_changed = 1 WHERE id = ?", (hashed, user_id))
    conn.commit()
    conn.close()

def generate_and_send_otp(to_email):
    otp = ''.join(str(random.randint(0, 9)) for _ in range(6))

    msg = EmailMessage()
    msg['Subject'] = "OTP Verification"
    msg['From'] = 'gerona_lorence@plpasig.edu.ph'
    msg['To'] = to_email
    msg.set_content(f"Your OTP is: {otp}")

    try:
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login('gerona_lorence@plpasig.edu.ph', 'hlop nqjr qujz utmp')  # ⚠️ App password
        server.send_message(msg)
        server.quit()
        return otp
    except Exception as e:
        print("Error sending email:", e)
        return None

# Routes

@app.route('/')
def index():
    return redirect(url_for('login'))

# Login Route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        user = get_user_by_email(email)

        if user and hash_password(password) == user[2]:
            session['user_id'] = user[0]
            session['email'] = user[1]

            if user[3] == 0:  # Password hasn't been changed
                return redirect(url_for('change_password', user_id=user[0]))
            else:
                flash('Login successful!', 'success')
                return redirect(url_for('dashboard'))
        else:
            flash('Invalid email or password.')
    return render_template('login.html')

# Dashboard Route
@app.route('/dashboard')
def dashboard():
    if 'user_id' not in session:
        return redirect(url_for('login'))  # If no user is logged in, redirect to login page
    return render_template('dashboard.html')  # Create a dashboard.html page for your dashboard


# Forgot Password Route
@app.route('/forgot-password', methods=['GET', 'POST'])
def forgot_password():
    if request.method == 'POST':
        email = request.form.get('email')
        user = get_user_by_email(email)

        if not user:
            flash("Email not found.")
            return render_template('forgot_password.html')

        otp = generate_and_send_otp(email)
        if otp is None:
            flash("Failed to send OTP. Please try again.")
            return render_template('forgot_password.html')

        session['otp'] = otp
        session['reset_email'] = email
        return redirect(url_for('verify_otp'))

    return render_template('forgot_password.html')

# OTP Verification Route
@app.route('/verify-otp', methods=['GET', 'POST'])
def verify_otp():
    if request.method == 'POST':
        entered_otp = request.form.get('otp')

        if entered_otp != session.get('otp'):
            flash("Invalid OTP. Please try again.")
            return render_template('verify_otp.html')

        return redirect(url_for('reset_password'))

    return render_template('verify_otp.html')

# Reset Password Route
@app.route('/reset-password', methods=['GET', 'POST'])
def reset_password():
    if request.method == 'POST':
        new_password = request.form.get('new_password')
        confirm_password = request.form.get('confirm_password')

        if new_password != confirm_password:
            flash("Passwords do not match.")
            return render_template('reset_password.html')

        email = session.get('reset_email')
        user = get_user_by_email(email)

        if user:
            update_password(user[0], new_password)
            return redirect(url_for('password_changed'))

        flash("Error resetting password. Please try again.")
        return render_template('reset_password.html')

    return render_template('reset_password.html')

# Change Password
@app.route('/change-password/<int:user_id>', methods=['GET', 'POST'])
def change_password(user_id):
    # Only allow access if user is in session and it's their own account
    if 'user_id' not in session or session['user_id'] != user_id:
        flash("Unauthorized access.")
        return redirect(url_for('login'))

    if request.method == 'POST':
        new_pass = request.form['new_password']
        confirm_pass = request.form['confirm_password']

        if new_pass != confirm_pass:
            flash('Passwords do not match.')
            return redirect(url_for('change_password', user_id=user_id))

        update_password(user_id, new_pass)

        # Clear session so they have to log in again
        session.clear()
        flash('Password changed successfully. Please log in.')
        return redirect(url_for('password_changed'))

    return render_template('change_password.html', user_id=user_id)


# Password Changed
@app.route('/password-changed')
def password_changed():
    return render_template('password_changed.html')

# Logout Route
@app.route('/logout')
def logout():
    session.clear()
    flash('Logged out.')
    return redirect(url_for('login'))

if __name__ == '__main__':
    app.run(debug=True)

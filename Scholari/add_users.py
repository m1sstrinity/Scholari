import sqlite3
import hashlib

def hash_password(password):
    """Hashes the password using SHA256."""
    return hashlib.sha256(password.encode()).hexdigest()

def add_multiple_users(users):
    """Adds multiple users with temporary passwords to the database."""
    conn = sqlite3.connect('scholari_db.db')
    cursor = conn.cursor()

    # Temporary password
    temp_password = 'TempPass123'
    hashed_password = hash_password(temp_password)

    # Insert each user into the database
    for email in users:
        try:
            cursor.execute("""
                INSERT INTO users (email, password, password_changed)
                VALUES (?, ?, 0)
            """, (email, hashed_password))

            conn.commit()
            print(f"User created successfully with email: {email}")
        except sqlite3.IntegrityError:
            print(f"Error: User with email {email} already exists.")
    
    conn.close()

# List of emails to add to the database
emails_to_add = [
    'capalaranzaneallyson@gmail.com'
]

# Add users to the database
add_multiple_users(emails_to_add)

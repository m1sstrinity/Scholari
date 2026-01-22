import sqlite3
import hashlib

# Helper function to hash the password
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

# Connect to the database
conn = sqlite3.connect('scholari_db.db')  # Ensure this is the correct path to your database
cursor = conn.cursor()

# Define test user details
email = 'student1@example.com'
temp_password = 'TempPass123'  # Temporary password
hashed_password = hash_password(temp_password)

# Try to insert the user into the database
try:
    cursor.execute("""
        INSERT INTO users (email, password, password_changed)
        VALUES (?, ?, 0)
    """, (email, hashed_password))
    conn.commit()
    print(f"User created with temporary password: {temp_password}")
except sqlite3.IntegrityError:
    print("User already exists.")

# Close the connection
conn.close()

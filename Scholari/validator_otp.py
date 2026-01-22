import random
import smtplib
from email.message import EmailMessage

otp = ""
for i in range(6):
    otp += str(random.randint(0,9))

server = smtplib.SMTP('smtp.gmail.com', 587)
server.starttls()

from_mail = 'gerona_lorence@plpasig.edu.ph'
server.login(from_mail, 'hlop nqjr qujz utmp')
to_mail = input("Enter your email: ")

msg = EmailMessage()
msg['Subject'] = "OTP Verification"
msg['From'] = from_mail
msg['To'] = to_mail
msg.set_content("Your OTP is: " + otp)

server.send_message(msg)

print("Email Sent")
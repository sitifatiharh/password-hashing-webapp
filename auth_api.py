from flask import Flask, request, jsonify
import hashlib
import re

app = Flask(__name__)

# Fungsi untuk hashing password
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

# Fungsi untuk mengecek kekuatan password
def is_strong_password(password):
    if len(password) < 8:
        return False, "Password must be at least 8 characters long"
    if not re.search(r"[A-Z]", password):
        return False, "Password must contain at least one uppercase letter"
    if not re.search(r"[0-9]", password):
        return False, "Password must contain at least one number"
    if not re.search(r"[!@#$%^&*(),-_:.?\"{}|<>]", password):
        return False, "Password must contain at least one special character"
    return True, "Password is strong"

# Endpoint untuk hashing password
@app.route("/hash", methods=["POST"])
def hash_password_api():
    data = request.get_json()
    password = data.get("password", "")
    hashed_password = hash_password(password)
    return jsonify({"hashed_password": hashed_password})

# Endpoint untuk mengecek kekuatan password
@app.route("/check_strength", methods=["POST"])
def check_strength_api():
    data = request.get_json()
    password = data.get("password", "")
    valid, message = is_strong_password(password)
    return jsonify({"valid": valid, "message": message})

# Menjalankan server Flask
if __name__ == "__main__":
    app.run(debug=True)

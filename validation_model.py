import pandas as pd
from sqlalchemy import create_engine
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report

# --- DB connection ---
engine = create_engine("mysql+pymysql://root@localhost/bakery_database")

# --- Load data from the 'vendor_validation' table ---
query = """
SELECT financial_score, compliance_score, reputation_score, approved
FROM vendor_validation
"""
data = pd.read_sql(query, engine)

# --- Features and label ---
X = data[["financial_score", "compliance_score", "reputation_score"]]
y = data["approved"]

# --- Train/Test split ---
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.5, random_state=42)

# --- Model training ---
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# --- Evaluate model ---
y_pred = model.predict(X_test)
print("Accuracy:", accuracy_score(y_test, y_pred))
print(classification_report(y_test, y_pred))

# ✅ Function to use in Flask
def validate_vendor_logic(data: dict):
    """
    Predict vendor approval and score based on input scores.
    Expects a dictionary with keys: financial_score, compliance_score, reputation_score
    """
    required_keys = {"financial_score", "compliance_score", "reputation_score"}
    if not required_keys.issubset(data.keys()):
        raise ValueError(f"Missing one of the required fields: {required_keys}")

    new_vendor = pd.DataFrame([{
        "financial_score": data["financial_score"],
        "compliance_score": data["compliance_score"],
        "reputation_score": data["reputation_score"]
    }])

    predicted_score = model.predict_proba(new_vendor)[0][1] * 100
    prediction = model.predict(new_vendor)[0]  # 0 or 1

    return round(predicted_score, 2), int(prediction)

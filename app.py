from dotenv import load_dotenv
import os

load_dotenv()

from flask import Flask, request, jsonify
from demand_model import predict_demand

app = Flask(__name__)

@app.route("/predict-demand", methods=["POST"])
def demand():
    try:
        data = request.get_json()

        if isinstance(data, dict):
            data = [data]

        results = []
        for item in data:
            product_id = item.get('product_id')
            days = item.get('days', 30)

            if not product_id:
                return jsonify({"error": "Missing 'product_id' in one of the requests"}), 400

            try:
                prediction = predict_demand(product_id, days)
                results.append({
                    "product_id": product_id,
                    "days": days,
                    "predicted_demand": prediction
                })
            except Exception as e:
                results.append({
                    "product_id": product_id,
                    "days": days,
                    "predicted_demand": f"Error: {str(e)}"
                })

        return jsonify({"results": results}), 200

    except Exception as e:
        return jsonify({"error": "Invalid input or server error", "details": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)

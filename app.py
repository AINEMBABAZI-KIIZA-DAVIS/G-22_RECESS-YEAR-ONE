from dotenv import load_dotenv
import os

load_dotenv()

from flask import Flask, request, jsonify
from demand_model import predict_demand
from customer_segmentation_model import segment_customer_logic
from recommendation_engine_model import get_recommendations
from validation_model import validate_vendor_logic

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


@app.route("/segment-customer", methods=["POST"])
def segment_customer():
    try:
        data = request.get_json()
        segment = segment_customer_logic(data)
        return jsonify({"segment": segment}), 200
    except Exception as e:
        return jsonify({"error": "Customer segmentation failed", "details": str(e)}), 500


@app.route("/recommend-products", methods=["POST"])
def recommend():
    try:
        data = request.get_json()
        customer_id = data.get("customer_id")
        top_n = data.get("top_n", 3)

        if not customer_id:
            return jsonify({"error": "Missing 'customer_id'"}), 400

        recommendations = get_recommendations(customer_id, top_n)
        return recommendations.to_json(orient="records")

    except Exception as e:
        return jsonify({"error": "Recommendation failed", "details": str(e)}), 500


@app.route("/validate-vendor", methods=["POST"])
def validate_vendor():
    try:
        data = request.get_json()
        score, prediction = validate_vendor_logic(data)
        return jsonify({
            "validation_score": score,
            "approved": bool(prediction)
        }), 200
    except Exception as e:
        return jsonify({"error": "Vendor validation failed", "details": str(e)}), 500


if __name__ == "__main__":
    app.run(debug=True)

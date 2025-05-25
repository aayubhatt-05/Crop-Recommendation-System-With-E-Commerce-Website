import numpy as np
from flask import Flask, request, jsonify, render_template
import pickle
from flask_cors import CORS
import pandas as pd

app = Flask(__name__)
CORS(app)

try:
    model = pickle.load(open('./artifact/crop_prediction_model.pkl', 'rb'))
    print("Model loaded successfully.")
except Exception as e:
    print(f"Error loading model: {e}")
    exit(1)

feature_names = ['N', 'P', 'K', 'temperature', 'humidity', 'pH', 'rainfall']

@app.route('/predict', methods=['POST'])
def predict():
    try:
        json_data = request.get_json()
        if not json_data:
            return jsonify({"res": "Error", "output": "No data provided"}), 400

        int_features = [
            float(json_data.get('N', 0)),
            float(json_data.get('P', 0)),
            float(json_data.get('K', 0)),
            float(json_data.get('temperature', 0)),
            float(json_data.get('humidity', 0)),
            float(json_data.get('pH', 0)),
            float(json_data.get('rainfall', 0))
        ]

        final_features = pd.DataFrame([int_features], columns=feature_names)
        prediction = model.predict(final_features)
        output = prediction[0]

        response = jsonify({
            "res": "Success",
            "output": str(output)
        })
        print(f"Prediction response: {response.get_data(as_text=True)}")  # Log the exact response
        return response

    except Exception as e:
        error_msg = f"Error during prediction: {str(e)}"
        print(error_msg)
        return jsonify({"res": "Error", "output": error_msg}), 500

if __name__ == "__main__":
    app.run(host='127.0.0.1', port=5000, debug=True)
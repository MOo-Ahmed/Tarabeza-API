import Engine
import time
import pandas as pd
from flask import Flask, request, jsonify, render_template


app = Flask(__name__)


@app.route('/restaurants/recommendations/<userId>')
def predict(userId):
    engine = Engine.RecommendationEngine(userId)
    response = app.response_class(
            response=engine.run(),
            status=200,
            mimetype='application/json'
        )
        
    return response


if __name__ == "__main__":
    app.run(debug=True, port=5000)

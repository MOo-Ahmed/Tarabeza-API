import Classifier
import time
from flask import Flask, request, jsonify, render_template


app = Flask(__name__)


@app.route('/reservations/detect/<userID>/<restID>')
def predict(restID, userID):
    classifier = Classifier.Classifier()
    
    response = app.response_class(
            response=classifier.run(userID, restID),
            status=200,
            mimetype='application/json'
        )
        
    return response

if __name__ == "__main__":
    app.run(debug=True, port=5000)

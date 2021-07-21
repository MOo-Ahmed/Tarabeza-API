import Engine
import time
from flask import Flask, request, jsonify, render_template
import Engine

app = Flask(__name__)


@app.route('/restaurant/branch/recommendation/<restID>')
def predict(restID):
    engine = Engine.Engine(restID)
    
    response = app.response_class(
        response=engine.run(),
        status=200,
        mimetype='application/json'
    )
        
    return response

if __name__ == "__main__":
    app.run(debug=True, port=5000)

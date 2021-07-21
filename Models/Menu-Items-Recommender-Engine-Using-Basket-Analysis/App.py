import pandas as pd
from pandas import ExcelWriter
from pandas import ExcelFile
import Engine
import time
from flask import Flask, request, jsonify, render_template


app = Flask(__name__)


@app.route('/menu/recommendations/<restID>/<catID>')
def predict(restID, catID):
    #Expresses the number of items of each category to return
    n = 4
    suffix = prepare(catID)
    engine = Engine.RecommendationEngine(suffix, restID, n)
    
    response = app.response_class(
            response=engine.run(),
            status=200,
            mimetype='application/json'
        )
        
    return response

def prepare(ctgID):
    # loading the data into lists
    knlg_base_filename = "Knowledge-Base.xls"
    # loading the rules
    suffix = []
    df2 = pd.read_excel(knlg_base_filename)
    pre = df2['prefix']
    for i in range (len(pre)):
            if pre[i] == ctgID :
                    suffix = [df2['suffix1'][i], df2['suffix2'][i], df2['suffix3'][i]]
    return suffix


if __name__ == "__main__":
    app.run(debug=True, port=5000)

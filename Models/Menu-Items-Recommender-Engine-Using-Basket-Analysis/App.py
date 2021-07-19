import pandas as pd
from pandas import ExcelWriter
from pandas import ExcelFile
import Engine
import time

#These properties are to be changed according to the request, this is a sample
itemID = 210
restaurantID = 4
ctgID = 4

# loading the data into lists
knlg_base_filename = "Knowledge-Base.xls"
#Expresses the number of items of each category to return
n = 4

# loading the rules
suffix = []
df2 = pd.read_excel(knlg_base_filename)
pre = df2['prefix']
for i in range (len(pre)):
        if pre[i] == ctgID :
                suffix = [df2['suffix1'][i], df2['suffix2'][i], df2['suffix3'][i]]

engine = Engine.RecommendationEngine(suffix, itemID, restaurantID, n)
o = engine.run()
print(o)

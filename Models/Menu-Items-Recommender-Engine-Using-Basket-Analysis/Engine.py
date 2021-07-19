import pandas as pd
from pandas import ExcelWriter
from pandas import ExcelFile
import numpy as np
import math
import pickle
import json
import random
import DB

suffix = None

class RecommendationEngine :

    recommendation_name = 'menu-items'
    
    def __init__(self, sfx, itemID, restaurantID, n):
        global suffix 
        self.itemID = itemID
        self.restID = restaurantID
        self.n = n
        if suffix == None :
            suffix = sfx

    def run (self):
        '''
        # loading the data into lists
        df = pd.read_excel(self.dataset_filename)
        with open('saved-items.pkl', 'wb') as f:
            pickle.dump(combined, f)
            
        with open('saved-items.pkl', 'rb') as f:
            combined = pickle.load(f)

        ids = df['id']
        ctgIDs = df['ctg_id']
        names = df['name']
        prices = df['price']
        combined = [ctgIDs , ids, names, prices]
        i = self.getItems(suffix, combined)
        jsonData =  { 'Recommendation-type' : 'menu items', 'Data' : i}
        jsonStr = json.dumps(jsonData)
        return jsonStr
        '''
        db = DB.DB()
        i = db.getRecommendations(suffix, self.n, self.restID)
        jsonData =  { 'Recommendation-type' : 'menu items', 'Data' : i}
        jsonStr = json.dumps(jsonData)
        return jsonStr
        
    #Closed - Method to get the association rule from the knowledge base
    def getSuffixIDs(self, prefix):
        df = pd.read_excel(self.knlg_base_filename)
        pre = df['prefix']
        for i in range (len(pre)):
            if pre[i] == prefix :
                return [df['suffix1'][i], df['suffix2'][i], df['suffix3'][i]]
         
    #Method to randomly pick items from each category
    def getItems(self, IDs, combined):
        items = []
        for i in range (len(combined[1])):
            if combined[0][i] in IDs :
                items.append([combined[1][i] , combined[2][i] , combined[3][i] ])
        random.shuffle(items)
        
        picked = items[:self.n]
        IDs = []
        for i in range (0, self.n):
            IDs.append(self.Convert(picked[i]))
        return IDs

    def Convert(self, lst):
        res_dct = {"id" : int(lst[0]), "name" : str(lst[1]) , "price" : int(lst[2]) }
        return res_dct
                

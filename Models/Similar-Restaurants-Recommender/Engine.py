import pandas as pd
import numpy as np
import math
import pickle
import json
import DB
import sys


class RecommendationEngine :
    
    def __init__(self, uid):
        self.uid = uid
   
    def getNearestRestaurants(self, match, x, y):
        distances = []
        n = len(match)
        for i in range (n):
            d = math.sqrt((match[i]['longitude'] - x)**2 + (match[i]['latitude'] - y)**2)
            
            # The Euclidean distance between two coordinates having actual distance = 1 kilometer
            coordinateDistanceOfOneKilometer = 0.0095481163 
            d = round(d / coordinateDistanceOfOneKilometer, 2)
            #id , distance , name
            pair = {"restaurant_id" : int(match[i]['restaurant_id']), "distance" : int(d) , "restaurant_name" : str(match[i]['restaurant_name']), "category_name" : str(match[i]['category_name'])}
            distances.append(pair)
        distances.sort(key=lambda x: x['distance'])
        l = []
        k = 15
        for i in range (k) :
            distance = distances[i]['distance']
            distance_exp = ''
            if distance < 1:
                distance *= 1000
                distance_exp = str(distance) + " m away"
            else :
                distance_exp = str(distance) + ' km away'
            restaurant = {"restaurant_id" : int(match[i]['restaurant_id']), "distance" : str(distance_exp) , "restaurant_name" : str(match[i]['restaurant_name']), "category_name" : str(match[i]['category_name'])}
            l.append(restaurant)
        
        return l

    def Convert(self, lst):
        res_dct = {"id" : int(lst[0]), "name" : str(lst[2]) , "distance" : str(lst[1]) }
        return res_dct
        
    def run (self):
        db = DB.DB()
        #Get the preferences
        location = db.getCustomerLocation(self.uid)
        match = db.getMatchingRestaurants(self.uid)
        #print(location, file=sys.stderr)
        if not location or not match:
            jsonData =  { 'Recommendation-type' : 'nearest similar restaurants', 'data' : []}
            jsonStr = json.dumps(jsonData)
            return jsonStr

        d = self.getNearestRestaurants(match, location[0] , location[1])
        jsonData =  { 'Recommendation-type' : 'nearest similar restaurants', 'data' : d}
        jsonStr = json.dumps(jsonData)
        return jsonStr    
        #print(match)
        

import pandas as pd
import numpy as np
from pandas import DataFrame
from sklearn.cluster import KMeans
import math
import pickle
import json
from scipy.cluster.vq import kmeans,vq
import DB

class Engine :
    threshold = 4
    def __init__(self, restID):
        self.restID = restID
    
    def get_best_location(self, cent, clus):
        MAX = 0
        IDX = 0
        for i in range(len(clus)):
            if clus[i] > MAX:
                IDX = i
                MAX = clus[i]
        return cent[IDX]
    def Convert(self, lst):
        res_dct = {"longitude" : float(lst[0]), "latitude" : float(lst[1]) , "distance" : float(lst[2])}
        return json.dumps(res_dct)

    def run (self):
        db = DB.DB()
        df = db.fetchLocations(self.restID, self.threshold)
        centroids,_ = kmeans(df,2)
        # assign each sample to a cluster
        idx,_ = vq(df,centroids)
        #Print number of elements per cluster
        #print (np.bincount(idx))
        #print(centroids)
        location = self.get_best_location(centroids, np.bincount(idx))
        loc_json = self.Convert(location)
        return loc_json
        

        











        
        

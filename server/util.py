import pickle
import json
import numpy as np

__columns=None
__model=None
__map=None

def get_rec_crop(nitrogen,phosphorus,potassium,temperature,humidity,ph,rainfall):
    x=np.zeros(len(__columns))
    x[0]=nitrogen
    x[1]=phosphorus
    x[2]=potassium
    x[3]=temperature
    x[4]=humidity
    x[5]=ph
    x[6]=rainfall
    value=__model.predict([x])
    for crop, number in __map.items():
            if number == value:
                return crop
    return None

def load_saved_artifacts():
    print("loading saved artifact...start")
    global __columns
    global __map

    with open("./artifact/columns.json","r") as f:
        __columns=json.load(f)['data-columns']

    with open("./artifact/crop_map.json","r") as f:
        __map=json.load(f)

    global __model

    with open("./artifact/crop_prediction_model.pkl","rb") as f:
        __model=pickle.load(f)
    print("loading saved artifact...done")

def get_columns():
    return __columns

def get_map():
    return __map

if __name__ == '__main__' :
    load_saved_artifacts()
    print(get_map())
    print(get_columns())




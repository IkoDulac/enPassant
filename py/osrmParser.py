#!/usr/bin/env python3

"""
Script to parse OSRM request response into an array and send it to a PHP script via HTTP.
requires polyline lib : # apt install python3-polyline
"""

import sys
import json
import polyline

# Open the input file
with open(sys.argv[1]) as f:
    osrm_response = json.load(f)
"""
allNodes = []
# 2do -> add the routes array

for route in osrm_response['routes']:
    # 2do -> append to the routes array
    for leg in route['legs']:
        for step in leg['steps']:
            for intersection in step['intersections']:
                 node = intersection['location']
                 allNodes.append(node)

latlngs = [i[::-1] for i in allNodes]

print(json.dumps(latlngs))
"""
allNodes = polyline.decode(osrm_response['routes'][0]['geometry'])
print(json.dumps(allNodes))

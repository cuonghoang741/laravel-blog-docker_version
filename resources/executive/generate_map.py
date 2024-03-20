from folium import Map, FeatureGroup, DivIcon

def generate_map_html(locations):
    """
    Generates the HTML code for a Folium map with custom markers representing images for the given locations.

    Args:
        locations: A list of dictionaries containing location information.

    Returns:
        The HTML code as a string.
    """
    # Create a base map centered on the first location
    map = Map(location=[float(locations[0]['latitude']), float(locations[0]['longitude'])], zoom_start=12)

    # Create a feature group to store markers
    feature_group = FeatureGroup(name="My Markers")

    # Add custom markers to the feature group
    for location in locations:
        lat = float(location['latitude'])
        lng = float(location['longitude'])
        img_url = location['photo']['images']['thumbnail']['url']
        name = location['name']
        color_code = location.get('color_code', 'white')
        cluster_id = int(location.get('cluster_id', 999)) + 1
        html_content = f'<div style="position: relative;"><img src="{img_url}" alt="{name}" style="width:50px;height:50px;border: solid {color_code} 4px;"><p style="padding:5px 2px;background:{color_code};color:white">{cluster_id}</p><div style="position: absolute;bottom: -22px;color: white;background: #00000057;text-wrap: nowrap;padding: 2px 5px;left: 50%;transform: translateX(-50%);">{name}</div></div>'
        icon = DivIcon(html=html_content)
        marker = feature_group.add_child(Marker(location=[lat, lng], icon=icon))

    # Add feature group to the map
    map.add_child(feature_group)

    # Get the HTML representation of the map
    html_output = map._repr_html_()

    return html_output

generate_map_html(locations)

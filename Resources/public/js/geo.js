var terra = 6372.795477598;
function distanza(da_lat, da_lon, a_lat, a_lon) {
    return (Math.acos((Math.sin(deg2rad(da_lat)) * Math.sin(deg2rad(a_lat))) + (Math.cos(deg2rad(da_lat)) * Math.cos(deg2rad(a_lat)) * Math.cos(deg2rad(da_lon - a_lon))))) * terra;
}

function deg2rad(radians) {
    return radians * (Math.PI / 180);
}

function km(distanza) {
    if (distanza < 0) {
        return 'n.d.';
    }
    if (distanza < 1) {
        distanza = distanza * 1000;
        if (distanza < 1) {
            return '1m';
        }
        if (distanza < 100) {
            return Math.round(distanza) + 'm';
        }
        return Math.round(distanza, -1) + 'm';
    }
    if (distanza < 10) {
        return Math.round(distanza, 1) + 'km';
    }
    return Math.round(distanza) + 'km';
}

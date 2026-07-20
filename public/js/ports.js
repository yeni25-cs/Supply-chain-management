document.addEventListener("DOMContentLoaded", function () {

    if(typeof PORT_DATA === "undefined") return;

    const map = L.map("portMap").setView(
        [PORT_DATA.country.lat, PORT_DATA.country.lon],
        5
    );

    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            attribution: "© OpenStreetMap"
        }
    ).addTo(map);

    const markers = [];

    PORT_DATA.ports.forEach(function(port){

        const marker = L.marker([
            port.lat,
            port.lon
        ]).addTo(map);

        marker.bindPopup(`
            <b>${port.name}</b><br>
            City : ${port.city}<br>
            Risk : ${port.risk}
        `);

        markers.push({
            name: port.name.toLowerCase(),
            marker: marker,
            lat: port.lat,
            lon: port.lon
        });

    });

    const search = document.getElementById("searchPort");

    if(search){

        search.addEventListener("keyup", function(){

            const keyword = this.value.toLowerCase();

            markers.forEach(function(item){

                if(item.name.includes(keyword)){

                    map.setView(
                        [item.lat,item.lon],
                        10
                    );

                    item.marker.openPopup();

                }

            });

        });

    }

});
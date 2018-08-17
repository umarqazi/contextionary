var myMap;
var myLatlng = new google.maps.LatLng(42.300169,-83.709188);
function initialize() {
    var mapOptions = {
        zoom: 13,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP  ,
        scrollwheel: false
    }
    myMap = new google.maps.Map(document.getElementById('map'), mapOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: myMap,
        title: 'Contextionary',
        icon: 'http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png'
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
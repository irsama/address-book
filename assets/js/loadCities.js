countryDropDown = document.getElementById('address_chosenCountry');
countryDropDown.addEventListener("change", loadXMLDoc);
cityDropDown = document.getElementById('address_chosenCity');
id = countryDropDown.value;
if(cityDropDown.options[0].text === 'select your city') {
    cityDropDown.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = 'select your city';
    cityDropDown.appendChild(opt);
}
if(id!=''){
    loadXMLDoc();
}
function loadXMLDoc() {
    const id = countryDropDown.value;
    if(id != '') {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    const cities = JSON.parse(xmlhttp.responseText);
                    cityDropDown.innerHTML = '';
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = 'select your city';
                    cityDropDown.appendChild(opt);
                    cities.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city.id;
                        opt.innerHTML = city.title;
                        cityDropDown.appendChild(opt);
                    });
                } else if (xmlhttp.status == 400) {
                    alert('There was an error 400');
                } else {
                    alert('something else other than 200 was returned');
                }
            }
        };
        xmlhttp.open("GET", "country/cities/" + id, true);
        xmlhttp.send();
    }else {
        cityDropDown.innerHTML = 'select your city';
    }
}
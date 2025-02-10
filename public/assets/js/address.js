mapboxgl.accessToken = 'sk.eyJ1IjoiZmFpdGhkaW5ubyIsImEiOiJjbTNlaTBpemYwZGg0MmlxeHBvdmN1Njc1In0.DxTSIOGOsItCew8yHtscJw';;
const addressInput = document.getElementById('address-input');
const suggestions = document.getElementById('suggestions');
const provinceField = document.getElementById('province');
const cityField = document.getElementById('city');
const postalCodeField = document.getElementById('postal-code');

addressInput.addEventListener('input', () => {
    const query = addressInput.value;
    if (query.length < 3) {
        suggestions.innerHTML = '';
        return;
    }

    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}&autocomplete=true&country=CA`)
        .then(response => response.json())
        .then(data => {
            suggestions.innerHTML = '';
            data.features.forEach(feature => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.textContent = feature.place_name;
                item.addEventListener('click', () => {
                    addressInput.value = feature.place_name;
                    suggestions.innerHTML = '';
                    populateAddressDetails(feature);
                });
                suggestions.appendChild(item);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

// Update cities based on the provided province and set the detected city as selected
async function updateCities(province, selectedCity = null) {
    const citySelect = document.getElementById("city");

    // Clear existing city options
    citySelect.innerHTML = "<option value=''>--Select a City--</option>";

    if (!province) return;

    try {
        const response = await fetch(`/auth/sign-up-cities/${encodeURIComponent(province)}`);
        if (!response.ok) throw new Error("Failed to fetch cities");

        const cities = await response.json();

        // Populate city dropdown with options from the response
        cities.forEach(city => {
            const option = document.createElement("option");
            option.value = city;
            option.textContent = city;

            // Check if the current city matches the selectedCity
            if (city === selectedCity) {
                option.selected = true;
            }

            citySelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching cities:", error);
    }
}

async function populateAddressDetails(feature) {
    provinceField.value = '';
    cityField.innerHTML = '<option value="">--Select a City--</option>';
    postalCodeField.value = '';

    const unitNumberField = document.querySelector('input[name="unit_number"]');
    const houseNumberField = document.querySelector('input[name="house_number"]');
    const streetNameField = document.querySelector('input[name="street_name"]');
    let province = null;
    let city = null;

    // Clear unit number, house number, and street name fields
    unitNumberField.value = '';
    houseNumberField.value = '';
    streetNameField.value = '';
    // Parse the address components to extract province, city, and postal code
    // if (feature.context) {
    //     feature.context.forEach(component => {
    //         if (component.id.includes('region')) {
    //             provinceField.value = component.text;
    //             province = component.text; // Save province for cities fetch
    //         } else if (component.id.includes('place') || component.id.includes('locality')) {
    //             city = component.text; // Save city to pass to updateCities
    //         } else if (component.id.includes('postcode')) {
    //             postalCodeField.value = component.text;
    //         }
    //     });
    // }

     // Parse the address components to extract relevant fields
     if (feature.context) {
        feature.context.forEach(component => {
            if (component.id.includes('region')) {
                provinceField.value = component.text;
                province = component.text; // Save province for cities fetch
            } else if (component.id.includes('place') || component.id.includes('locality')) {
                city = component.text; // Save city to pass to updateCities
            } else if (component.id.includes('postcode')) {
                // postalCodeField.value = component.text;
                // postalCodeField.value = component.text;
            } else if (component.id.includes('address')) {
                houseNumberField.value = component.text; // Populate house number
            } else if (component.id.includes('street')) {
                streetNameField.value = component.text; // Populate street name
            } else if (component.id.includes('place.unit')) {
                unitNumberField.value = component.text; // Populate unit number
            }
        });
    }


    // Populate fields if address is provided directly
    if (feature.address) {
        houseNumberField.value = feature.address;
    }

    // If a province was detected, fetch the cities for that province and set the detected city
    if (province) {
        await updateCities(province, city);
    }
}

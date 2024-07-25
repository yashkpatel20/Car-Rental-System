
let feature_s_form = document.getElementById('feature_s_form');
let facility_s_form = document.getElementById('facility_s_form');

feature_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_feature();
});

function add_feature() {
    let data = new FormData();
    data.append('name', feature_s_form.elements['feature_name'].value);
    data.append('add_feature', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);

    xhr.onload = function () {
        console.log(this.responseText);
        var myModal = document.getElementById('feature-s');
        var modal = bootstrap.Modal.getInstance(myModal); // Use 'Modal' instead of 'Model'
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'New Feature Added!');
            feature_s_form.elements['feature_name'].value = '';
            get_features();
        } else {
            alert('error', 'Server Down!');
        }
    }
    xhr.send(data);

}

function get_features() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        document.getElementById('features-data').innerHTML = this.responseText;
    };
    xhr.send('get_features');
}

function rem_feature(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Feature Removed Successfully!');
            get_features();
        } else if (this.responseText == 'car_added') {
            alert('error', 'Feature Already Added in Cars!');
        } else {
            alert('error', 'Server Down!');
        }

    };
    xhr.send('rem_feature=' + val);
}


facility_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_facility();
});

function add_facility() {
    let data = new FormData();
    data.append('name', facility_s_form.elements['facility_name'].value);
    data.append('icon', facility_s_form.elements['facility_icon'].files[0]);
    data.append('description', facility_s_form.elements['facility_description'].value);
    data.append('add_facility', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);

    xhr.onload = function () {
        console.log(this.responseText);
        var myModal = document.getElementById('facility-s');
        var modal = bootstrap.Modal.getInstance(myModal); // Use 'Modal' instead of 'Model'
        modal.hide();

        if (this.responseText == 'inv_img') {
            alert('error', 'Only SVG image are allowed!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image Should be less then 1MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image  Upload Failed. Server Down!');
        } else {
            alert('success', 'New Facility Added!');
            facility_s_form.reset();
            get_facilities();
        }
    }
    xhr.send(data);

}

function get_facilities() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        document.getElementById('facilities-data').innerHTML = this.responseText;
    };
    xhr.send('get_facilities');
}

function rem_facility(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Facility Removed Successfully!');
            get_facilities();
        } else if (this.responseText == 'car_added') {
            alert('error', 'Facility Already Added in Cars!');
        } else {
            alert('error', 'Server Down!');
        }

    };
    xhr.send('rem_facility=' + val);
}


spec_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_specification();
});

function add_specification() {
    let data = new FormData();
    data.append('name', spec_s_form.elements['spec_name'].value);
    data.append('icon', spec_s_form.elements['spec_icon'].files[0]);
    data.append('add_specification', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);

    xhr.onload = function () {
        var myModal = document.getElementById('spec-s');
        var modal = bootstrap.Modal.getInstance(myModal); // Use 'Modal' instead of 'Model'
        modal.hide();

        if (this.responseText == 'inv_img') {
            alert('error', 'Only SVG image are allowed!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image Should be less then 1MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image  Upload Failed. Server Down!');
        } else {
            alert('success', 'New Specifications Added!');
            spec_s_form.reset();
            get_specifications();
        }
    }
    xhr.send(data);

}

function get_specifications() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        document.getElementById('spec-data').innerHTML = this.responseText;
    };
    xhr.send('get_specifications');
}

function rem_specification(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/features_facilities.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Specification Removed Successfully!');
            get_specifications();
        } else if (this.responseText == 'specification_added') {
            alert('error', 'Specification Already Added in Cars!');
        } else {
            alert('error', 'Server Down!');
        }

    };
    xhr.send('rem_specification=' + val);
}



window.onload = function () {
    get_features();
    get_facilities();
    get_specifications();
}

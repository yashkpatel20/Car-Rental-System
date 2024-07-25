let add_car_form = document.getElementById('add_car_form');
add_car_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_car();
});

function add_car() {
    let data = new FormData();
    data.append('add_car', '');
    data.append('name', add_car_form.elements['name'].value);
    data.append('company', add_car_form.elements['company'].value);
    data.append('sit', add_car_form.elements['sit'].value);
    data.append('price_day', add_car_form.elements['price_day'].value);
    data.append('price_hour', add_car_form.elements['price_hour'].value);
    data.append('fuel_type', add_car_form.elements['fuel_type'].value);
    data.append('model', add_car_form.elements['model'].value);
    data.append('car_type', add_car_form.elements['car_type'].value);
    data.append('air_bags', add_car_form.elements['air_bags'].value);
    data.append('boot_capacity', add_car_form.elements['boot_capacity'].value);
    data.append('displacement', add_car_form.elements['displacement'].value);
    data.append('fuel_tank_capacity', add_car_form.elements['fuel_tank_capacity'].value);
    data.append('cng_capacity', add_car_form.elements['cng_capacity'].value);
    data.append('transmission_types', add_car_form.elements['transmission_types'].value);
    data.append('mileage', add_car_form.elements['mileage'].value);
    data.append('description', add_car_form.elements['description'].value);

    let features = [];
    add_car_form.elements['features'].forEach(el => {
        if (el.checked) {
            features.push(el.value);
        }
    });

    data.append('features', JSON.stringify(features));

    // Serialize specifications from the form
    // let specifications = {};
    // <?php
    // $res = selectAll("specifications");
    // while ($specs = mysqli_fetch_assoc($res)) {
    //     echo "specifications['{$specs['id']}'] = add_car_form.elements['specifications[{$specs['id']}]'].value;";
    // }
    // ?>

    // data.append('specifications', JSON.stringify(specifications));

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.onload = function() {
        var myModal = document.getElementById('add-car');
        var modal = bootstrap.Modal.getInstance(myModal); // Use 'Modal' instead of 'Model'
        modal.hide();

        if (this.responseText == '1') {
            alert('success', 'New Car Added!');
            add_car_form.reset();
            // get_all_cars();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send(data);
}

function get_all_cars() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        document.getElementById('car-data').innerHTML = this.responseText;
    };
    xhr.send('get_all_cars');
}

let edit_car_form = document.getElementById('edit_car_form');

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let data = JSON.parse(this.responseText);
        edit_car_form.elements['name'].value = data.cardata.name;
        edit_car_form.elements['company'].value = data.cardata.company;
        edit_car_form.elements['sit'].value = data.cardata.sit;
        edit_car_form.elements['price_day'].value = data.cardata.price_day;
        edit_car_form.elements['price_hour'].value = data.cardata.price_hour;
        edit_car_form.elements['fuel_type'].value = data.cardata.fuel_type;
        edit_car_form.elements['model'].value = data.cardata.model;
        edit_car_form.elements['car_type'].value = data.cardata.car_type;
        edit_car_form.elements['air_bags'].value = data.cardata.air_bags;
        edit_car_form.elements['boot_capacity'].value = data.cardata.boot_capacity;
        edit_car_form.elements['displacement'].value = data.cardata.displacement;
        edit_car_form.elements['fuel_tank_capacity'].value = data.cardata.fuel_tank_capacity;
        edit_car_form.elements['cng_capacity'].value = data.cardata.cng_capacity;
        edit_car_form.elements['transmission_types'].value = data.cardata.transmission_types;
        edit_car_form.elements['mileage'].value = data.cardata.mileage;
        edit_car_form.elements['description'].value = data.cardata.description;
        edit_car_form.elements['car_id'].value = data.cardata.id;

        edit_car_form.elements['features'].forEach(el => {
            if (data.features.includes(Number(el.value))) {
                el.checked = true;
            }
        });

    };
    xhr.send('get_car=' + id);
}

edit_car_form.addEventListener('submit', function(e) {
    e.preventDefault();
    submit_edit_car();
});

function submit_edit_car() {
    let data = new FormData();
    data.append('edit_car', '');
    data.append('car_id', edit_car_form.elements['car_id'].value);
    data.append('name', edit_car_form.elements['name'].value);
    data.append('company', edit_car_form.elements['company'].value);
    data.append('sit', edit_car_form.elements['sit'].value);
    data.append('price_day', edit_car_form.elements['price_day'].value);
    data.append('price_hour', edit_car_form.elements['price_hour'].value);
    data.append('fuel_type', edit_car_form.elements['fuel_type'].value);
    data.append('model', edit_car_form.elements['model'].value);
    data.append('car_type', edit_car_form.elements['car_type'].value);
    data.append('air_bags', edit_car_form.elements['air_bags'].value);
    data.append('boot_capacity', edit_car_form.elements['boot_capacity'].value);
    data.append('displacement', edit_car_form.elements['displacement'].value);
    data.append('fuel_tank_capacity', edit_car_form.elements['fuel_tank_capacity'].value);
    data.append('cng_capacity', edit_car_form.elements['cng_capacity'].value);
    data.append('transmission_types', edit_car_form.elements['transmission_types'].value);
    data.append('mileage', edit_car_form.elements['mileage'].value);
    data.append('description', edit_car_form.elements['description'].value);

    let features = [];
    edit_car_form.elements['features'].forEach(el => {
        if (el.checked) {
            features.push(el.value);
        }
    });

    data.append('features', JSON.stringify(features));
    // data.append('specifications', JSON.stringify(specifications));

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.onload = function() {
        var myModal = document.getElementById('edit-car');
        var modal = new bootstrap.Modal(myModal);
        modal.hide();

        if (this.responseText == '1') {
            alert('success', 'Car Data Updated!');
            edit_car_form.reset();
            get_all_cars();
        } else {
            alert('error', 'Server Down!');
        }
    };

    xhr.send(data);
}


function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.responseText == '1') {
            alert('success', 'Status toggled!');
            get_all_cars();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send('toggle_status=' + id + '&value=' + val);
}

let add_image_form = document.getElementById('add_image_form');
add_image_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_image();
});

function add_image() {
    let data = new FormData();

    data.append('image', add_image_form.elements['image'].files[0]);
    data.append('car_id', add_image_form.elements['car_id'].value);
    data.append('add_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);

    xhr.onload = function() {
        if (this.responseText == 'inv_img') {
            alert('error', 'Only JPG , WEBP or PNG  image are allowed!', 'image-alert');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image Should be less then 2MB!', 'image-alert');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image  Upload Failed. Server Down!', 'image-alert');
        } else {
            alert('success', 'New Image Added!', 'image-alert');
            car_images(add_image_form.elements['car_id'].value, document.querySelector('#car-images .modal-title').innerHTML)
            add_image_form.reset();
        }
    }
    xhr.send(data);

}

function car_images(id, cname) {
    document.querySelector('#car-images .modal-title').innerHTML = cname;
    add_image_form.elements['car_id'].value = id;
    add_image_form.elements['image'].value = '';

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        document.getElementById('car-image-data').innerHTML = this.responseText;
    }
    xhr.send('get_car_images=' + id);

}

function rem_image(img_id, car_id) {
    let data = new FormData();

    data.append('image_id', img_id);
    data.append('car_id', car_id);
    data.append('rem_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);

    xhr.onload = function() {
        if (this.responseText == 1) {
            alert('success', 'Image Deleted!', 'image-alert');
            car_images(car_id, document.querySelector('#car-images .modal-title').innerHTML);
        } else {
            alert('error', 'Image Delete Failed. Server Down!', 'image-alert');
        }
    }

    xhr.send(data);
}


function thumb_image(img_id, car_id) {
    let data = new FormData();

    data.append('image_id', img_id);
    data.append('car_id', car_id);
    data.append('thumb_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/cars.php', true);

    xhr.onload = function() {
        if (this.responseText == 1) {
            alert('success', 'Image Thumbnail Changed!', 'image-alert');
            car_images(car_id, document.querySelector('#car-images .modal-title').innerHTML);
        } else {
            alert('error', 'Image Thumbnail Update Failed !', 'image-alert');
        }
    }

    xhr.send(data);
}

function remove_car(car_id) {

    if (confirm('Are you sure ? , You want to Delete this Car??')) {
        let data = new FormData();
        data.append('car_id', car_id);
        data.append('remove_car', '');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/cars.php', true);

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Car Deleted!!');
                get_all_cars();
            } else {
                alert('error', 'Server Down');
            }
        }

        xhr.send(data);
    }
}




window.onload = function() {
    get_all_cars();
}
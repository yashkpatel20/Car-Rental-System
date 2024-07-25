function get_bookings(search='') {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/new_bookings.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('table-data').innerHTML = this.responseText;
    };
    xhr.send('get_bookings&search=' + search);
}

let assign_car_form = document.getElementById('assign_car_form');

function assign_car(id) {
    assign_car_form.elements['booking_id'].value = id;

}

assign_car_form.addEventListener('submit', function (e) {
    e.preventDefault();
    let data = new FormData();
    data.append('car_no', assign_car_form.elements['car_no'].value);
    data.append('booking_id', assign_car_form.elements['booking_id'].value);
    data.append('assign_car', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/new_bookings.php', true);

    xhr.onload = function () {
        var myModal = document.getElementById('assign-car');
        var modal = bootstrap.Modal.getInstance(myModal); // Use 'Modal' instead of 'Model'
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Car  Assigned!');
            assign_car_form.reset();
            get_bookings();
        } else {
            alert('error', 'Server Down!');
        }


    }
    xhr.send(data);
});

function cancel_booking(id) {
    if (confirm('Are you sure ? , You want to Cancel this Booking?')) {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('cancel_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/new_bookings.php', true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Booking Cancelled!');
                get_bookings();
            } else {
                alert('error', 'Server Down!');
            }
        }

        xhr.send(data);
    }
}




window.onload = function () {
    get_bookings();
}
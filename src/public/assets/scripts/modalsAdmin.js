document.addEventListener('DOMContentLoaded', function () {
    let modal = new bootstrap.Modal(document.getElementById('exampleModal'))

    let openModalBtns = document.querySelectorAll('.btn-warning')
    let closeModalBtn = document.querySelector('[data-bs-dismiss="modal"]')

    function openModal() {
        let row = this.closest('tr')
        let modalPage = document.getElementById('exampleModal')

        // -- MODAL FOR USERS -- //
        if (modalPage.classList.contains('users-modal')) {
            let userId = row.querySelector('td:first-child').innerText
            let lastName = row.querySelector('td:nth-child(2)').innerText
            let firstName = row.querySelector('td:nth-child(3)').innerText
            let tel = row.querySelector('td:nth-child(4)').innerText
            let email = row.querySelector('td:nth-child(5)').innerText
            let address = row.querySelector('td:nth-child(6)').innerText
            let isAdmin = row.querySelector('td:nth-child(7)').innerText

            document.getElementById('user-id-input').value = userId
            document.getElementById('recipient-lastname').value = lastName
            document.getElementById('recipient-firstname').value = firstName
            document.getElementById('recipient-tel').value = tel
            document.getElementById('recipient-email').value = email
            document.getElementById('recipient-address').value = address
            document.getElementById('recipient-isadmin').value = isAdmin

            // -- MODAL FOR BRANDS -- //
        } else if (modalPage.classList.contains('brands-modal')) {
            let brandId = row.querySelector('td:first-child').innerText
            let brandName = row.querySelector('td:nth-child(2)').innerText

            document.getElementById('brand-id-input').value = brandId
            document.getElementById('recipient-brand').value = brandName

            // -- MODAL FOR NB PLACE -- //
        } else if (modalPage.classList.contains('number-places-modal')) {
            let numberPlaceId = row.querySelector('td:first-child').innerText
            let numberPlaceName = row.querySelector('td:nth-child(2)').innerText

            document.getElementById('number-places-id-input').value = numberPlaceId
            document.getElementById('recipient-number-places').value = numberPlaceName

            // -- MODAL FOR COLORS -- //
        } else if (modalPage.classList.contains('colors-modal')) {
            let colorId = row.querySelector('td:first-child').innerText
            let colorName = row.querySelector('td:nth-child(2)').innerText

            document.getElementById('color-id-input').value = colorId
            document.getElementById('recipient-color').value = colorName

            // -- MODAL FOR VEHICLES  -- //
        } else if (modalPage.classList.contains('vehicles-modal')) {
            let vehicleId = row.querySelector('td:first-child').innerText
            let vehicleImage = row.querySelector('td:nth-child(2) img').getAttribute('src')
            let vehiculeName = row.querySelector('td:nth-child(3)').innerText
            let vehicleLocalisation = row.querySelector('td:nth-child(4)').innerText
            let vehicleBrand = row.querySelector('td:nth-child(5)').innerText
            let vehicleNumberPlace = row.querySelector('td:nth-child(6)').innerText
            let vehicleColor = row.querySelector('td:nth-child(7)').innerText
            let vehiclePrice = row.querySelector('td:nth-child(8)').innerText

            document.getElementById('vehicle-id-input').value = vehicleId
            document.getElementById('recipient-image').value = vehicleImage
            document.getElementById('recipient-name').value = vehiculeName
            document.getElementById('recipient-localisation').value = vehicleLocalisation
            document.getElementById('recipient-brand').value = vehicleBrand
            document.getElementById('recipient-number_place').value = vehicleNumberPlace
            document.getElementById('recipient-color').value = vehicleColor
            document.getElementById('recipient-price').value = vehiclePrice

            // -- MODAL FOR REVIEWS -- // review, stars
        } else if (modalPage.classList.contains('reviews-modal')) {
            let reviewId = row.querySelector('td:first-child').innerText
            let review = row.querySelector('td:nth-child(4)').innerText
            let stars = row.querySelector('td:nth-child(5)').innerText

            document.getElementById('review-id-input').value = reviewId
            document.getElementById('recipient-review').value = review
            document.getElementById('recipient-stars').value = stars

            // -- MODAL FOR RESERVATIONS -- //
        }

        modal.show()
    }

    function closeModal() {
        modal.hide()
    }

    openModalBtns.forEach(function (btn) {
        btn.addEventListener('click', openModal)
    })

    closeModalBtn.addEventListener('click', closeModal)
})

document.addEventListener('DOMContentLoaded', function () {
    let modalProfile = new bootstrap.Modal(document.getElementById('modalProfile'))
    let modalReview = new bootstrap.Modal(document.getElementById('modalReview'))

    let openModalBtnProfile = document.querySelectorAll('.btnUpdate')
    let openModalBtnReview = document.querySelectorAll('.btnReview')
    let closeModalBtnProfile = document.querySelector('.closeModalProfile')
    let closeModalBtnReview = document.querySelector('.closeModalReview')

    function openModalProfile() {
        modalProfile.show()
    }

    function openModalReview() {
        let vehicleName = this.getAttribute('data-vehicle-name');
        let vehicleId = this.getAttribute('data-vehicle-id');
        document.querySelector('#recipient-vehicle').textContent = `Véhicule : ${vehicleName}`;
        document.querySelector('#vehicle-id-input').value = vehicleId;
        modalReview.show();
    }

    function closeModalProfile() {
        modalProfile.hide()
    }

    function closeModalReview() {
        modalReview.hide()
    }

    openModalBtnProfile.forEach(function (btn) {
        btn.addEventListener('click', openModalProfile)
    })

    openModalBtnReview.forEach(function (btn) {
        btn.addEventListener('click', openModalReview)
    })

    closeModalBtnProfile.addEventListener('click', closeModalProfile)
    closeModalBtnReview.addEventListener('click', closeModalReview)
})

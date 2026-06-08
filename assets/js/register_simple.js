document.addEventListener('DOMContentLoaded', () => {
    const customerBtn = document.getElementById('customerBtn');
    const managerBtn = document.getElementById('managerBtn');
    const customerForm = document.getElementById('customerForm');
    const managerForm = document.getElementById('managerForm');

    function showCustomer() {
        customerBtn.classList.add('active');
        managerBtn.classList.remove('active');
        customerForm.classList.add('active');
        managerForm.classList.remove('active');
    }

    function showManager() {
        managerBtn.classList.add('active');
        customerBtn.classList.remove('active');
        managerForm.classList.add('active');
        customerForm.classList.remove('active');
    }

    customerBtn.addEventListener('click', showCustomer);
    managerBtn.addEventListener('click', showManager);

    // ابتدا فقط فرم مشتری را نمایش دهید
    showCustomer();
});
const today = new Date().toISOString().split('T')[0];
        
// Set the max attribute of the date input to the current date
document.getElementById('applicant_dob').setAttribute('max', today);
document.getElementById('deceased_doe').setAttribute('max', today);
document.getElementById('appl_date').setAttribute('max', today);

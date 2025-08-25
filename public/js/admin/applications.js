document.addEventListener('DOMContentLoaded', function() {
    // Toggle details row on clicking main table row
    const rows = document.querySelectorAll('tr.clickable-row');

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const appId = row.getAttribute('data-app-id');
            const detailRow = document.getElementById('details-' + appId);
            if (!detailRow) return;

            // Close other detail rows
            document.querySelectorAll('tr.app-details-row').forEach(r => {
                if (r.id !== 'details-' + appId) {
                    r.classList.add('d-none');
                }
            });

            // Toggle current detail row
            detailRow.classList.toggle('d-none');

            // Scroll into view if opened
            if (!detailRow.classList.contains('d-none')) {
                detailRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Handle UI updates inside each detail row when status/progress changes
    document.querySelectorAll('tr.app-details-row').forEach(detailRow => {
        const appId = detailRow.id.replace('details-', '');
        const statusSelect = detailRow.querySelector(`select[name="status"]`);
        const progressStage = detailRow.querySelector(`select[name="progress_stage"]`);
        const adminNotes = detailRow.querySelector(`#admin_notes-${appId}`); // ✅ fixed
        const revisionLink = detailRow.querySelector('.revision-link');
        const updateBtn = detailRow.querySelector('.update-btn');
        const labels = document.querySelector('label[for="progress_stage-' + appId + '"]');
        const archiveBtn = detailRow.querySelector('.archive-btn');
        const trashBtn = detailRow.querySelector('.trash-btn');

        if(!statusSelect || !progressStage || !adminNotes) return;

        function updateUI(status) {
            console.log('Updating UI for status:', status);
            switch(status) {
                case 'Revision Requested':
                    statusSelect.disabled = false;
                    progressStage.value = 'Revision request';
                    adminNotes.value = 'Your application will not proceed until the requested files are uploaded. if the requested files are not uploaded, the application will be rejected.';
                    progressStage.style.display = 'none';
                    updateBtn.style.display = 'none';
                    labels.style.display = 'none';
                    revisionLink.style.display = 'inline-block';
                    trashBtn.style.display = 'inline-block';
                    break;

                case 'Approved':
                    revisionLink.style.display = 'none';
                    progressStage.style.display = 'block';
                    labels.style.display = 'block';
                    updateBtn.style.display = 'block';
                    trashBtn.style.display = 'none';
                    archiveBtn.style.display = 'none';
                    adminNotes.value = 'Your application is being processed for licensing.';

                    // Show only allowed progress options
                    Array.from(progressStage.options).forEach(option => {
                        const allowed = ['Processing License', 'Ready for Release', 'Completed'];
                        option.style.display = allowed.includes(option.value) ? '' : 'none';
                    });

                    if(!['Processing License', 'Ready for Release', 'Completed'].includes(progressStage.value)){
                        progressStage.value = 'Processing License';
                    }

                    progressStage.onchange = () => {
                        switch(progressStage.value) {
                            case 'Processing License':
                                adminNotes.value = 'Your application is being processed for licensing.';
                                archiveBtn.style.display = 'none';
                                break;
                            case 'Ready for Release':
                                adminNotes.value = 'Your application is ready for release.';
                                archiveBtn.style.display = 'none';
                                break;
                            case 'Completed':
                                adminNotes.value = 'Congratulations! Your application has been completed.';
                                archiveBtn.style.display = 'block';
                                break;
                            default:
                                adminNotes.value = 'No notes available.';
                        }
                    };
                    break;

                case 'Under Review':
                    revisionLink.style.display = 'none';
                    progressStage.style.display = 'none';
                    adminNotes.value = 'Your application is currently under review.';
                    updateBtn.style.display = 'block';
                    trashBtn.style.display = 'none';
                    archiveBtn.style.display = 'none';
                    break;

                case 'Pending':
                    revisionLink.style.display = 'none';
                    progressStage.style.display = 'none';
                    progressStage.value = 'Submitted';
                    adminNotes.value = 'Your application has been submitted and is awaiting review.';
                    updateBtn.style.display = 'block';
                    trashBtn.style.display = 'none';
                    archiveBtn.style.display = 'none';
                    break;

                default: // Rejected or others
                    revisionLink.style.display = 'none';
                    progressStage.style.display = 'none';
                    progressStage.value = 'Rejected';
                    adminNotes.value = 'Your application has been rejected due to missing information or documentation.';
                    updateBtn.style.display = 'block';
                    trashBtn.style.display = 'none';
                    archiveBtn.style.display = 'block';
                    break;
            }
        }

        // Initialize UI on page load
        updateUI(statusSelect.value);

        // Listen for status changes
        statusSelect.addEventListener('change', () => {
            updateUI(statusSelect.value);
        });

        // On form submit, hide update button if status is Revision Requested
        const form = updateBtn.closest('form');
        if(form){
            form.addEventListener('submit', () => {
                if(statusSelect.value === 'Revision Requested'){
                    updateBtn.style.display = 'none';
                    revisionLink.style.display = 'inline-block';
                    progressStage.style.display = 'none';
                }
            });
        }
    });
});

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="modal-body">
                <i class="bi bi-x-circle text-danger display-4"></i>
                <h4 class="mt-3"><?= $error_message ?></h4>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Show Modal Automatically -->
<script>
    var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
    myModal.show();
</script>

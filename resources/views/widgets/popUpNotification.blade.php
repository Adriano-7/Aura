<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{ $message }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#notificationModal').modal('show');
    });
</script>

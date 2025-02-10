<div>
    <form action="{{ route('admin.auto-replies.store') }}" method="POST" id="createAutoReplyForm">
        <div class="modal-body">
            @csrf
            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma-separated)</label>
                <input type="text" name="keywords[]" class="form-control" placeholder="hello, hi" required>
            </div>
            <div class="mb-3">
                <label for="response" class="form-label">Response</label>
                <textarea name="response" class="form-control" rows="3" required></textarea>
            </div>
        </div>
    </form>
</div>

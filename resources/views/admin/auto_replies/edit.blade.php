<div>
    <form action="{{ route('admin.auto-replies.update', ['auto_reply' => $autoReply->id]) }}" method="POST" id="editAutoReplyForm">
        <div class="modal-body">
            @csrf
            @method('PUT') <!-- Use PUT for updating resources -->
            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma-separated)</label>
                <input 
                    type="text" 
                    name="keywords[]" 
                    class="form-control" 
                    placeholder="hello, hi" 
                    value="{{ is_array($autoReply->keywords) ? implode(', ', $autoReply->keywords) : (is_string($autoReply->keywords) ? $autoReply->keywords : '') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="response" class="form-label">Response</label>
                <textarea 
                    name="response" 
                    class="form-control" 
                    rows="3" 
                    required>{{ $autoReply->response }}</textarea> <!-- Prefill existing response -->
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="1" {{ $autoReply->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $autoReply->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </form>
</div>

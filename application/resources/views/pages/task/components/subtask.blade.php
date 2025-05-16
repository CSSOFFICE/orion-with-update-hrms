@foreach($subb as $comment)
<div class="display-flex flex-row comment-row" id="card_comment_{{ $comment->sub_task_id  }}">
    <div class="p-2 comment-avatar">
        <img src="{{ getUsersAvatar($comment->subtask_description, $comment->subtask_description) }}" class="img-circle"
            alt="{{ $comment->subtask_description ?? runtimeUnkownUser() }}" width="40">
    </div>
    <div class="comment-text w-100 js-hover-actions">
        <div class="row">
            <div class="col-sm-6 x-name">{{ $comment->subtask_description ?? '' }}</div>
            <div class="col-sm-6 x-meta text-right">
                <!--meta-->
                <span class="x-date"><small>{{ $comment->subtask_detail??'' }}</small></span>
                <!--actions: delete-->

            </div>
        </div>
        <div class="p-t-4">{!! clean($comment->status) !!}</div>
    </div>
</div>
@endforeach

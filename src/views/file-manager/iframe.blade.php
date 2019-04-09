{{--css--}}
<link rel="stylesheet" href="/css/filemanager.css?v=1.2">

{{--start model for S3 file manager --}}
<div class="modal filemanager-iframe" id="fileManagerModal" tabindex="-1" role="dialog" aria-labelledby="fileManagerModalLabel"
     aria-hidden="true">
    <div class="modal-header">
        <h3 id="fileManagerModalLabel">File Manager</h3>
    </div>
    <div class="modal-body">
        <iframe src="{{ url('/filemanager?path='.config('path.folder_name'))}}" id="file_manager"
                style="width: 100%;height: 100%"></iframe>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-theme-color waves-effect" data-dismiss="modal" aria-hidden="true" style="float: right;">Cancel</button>
    </div>
</div>
{{--end model for S3 file manager --}}

{{--scripts--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.close').click(function () {
            $('#fileManagerModal').fadeOut();
        });
    });
    $('#file_manager').on('load', function () {
        var iframe = $('#file_manager').contents();
        iframe.find("#mySelected").click(function () {
            let current = $('#fileManagerModal').attr('current');
            var images = [];
            var size = [];
            var height = [];
            var width = [];

            iframe.find('ul.img-gallery li').each(function () {
                if ($(this).hasClass('selected')) {
                    images.push($(this).find('img').data('value'));
                    size.push($(this).find('img').data('size'));
                    height.push($(this).find('img')[0].naturalHeight);
                    width.push($(this).find('img')[0].naturalWidth);
                }
            });

            if(current == '.trumbowyg-editor') {
                var img = '{{ env('AWS_URL').config('path.folder_name').'/' }}' + images ;
                $('input[name="url"]').val(img);
            } else {
                $(current).val(images);
                $('#images').val(images);
            }

            @if(!empty($validateSize))
                if(width >= 640 && height <= 1920){
                    $('#image-upload').val(images);
                    $('form#img-upload').submit();
                } else{
                    alert("Please upload a bigger image.");
                    return false;
                }
            @endif

            $('#fileManagerModal').modal('toggle');
        });
    });
</script>
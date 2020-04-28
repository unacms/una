<script>
   FilePond.registerPlugin(FilePondPluginImagePreview);
    FilePond.registerPlugin(FilePondPluginMediaPreview);
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    FilePond.registerPlugin(FilePondPluginImageEdit);


var oFilePondDefaultSettings = {
    allowReorder: true,
    /*imageEditEditor: Doka.create({
        utils: ['crop', 'filter', 'color']
    }),*/
    labelIdle: '<span class="filepond--label-action"><bx_text:_bx_media_uploader_filepond_labelIdle /></span>',
    labelInvalidField: '<bx_text:_bx_media_uploader_filepond_labelInvalidField />',
    labelFileWaitingForSize: '<bx_text:_bx_media_uploader_filepond_labelFileWaitingForSize />',
    labelFileSizeNotAvailable: '<bx_text:_bx_media_uploader_filepond_labelFileSizeNotAvailable />',
    labelFileCountSingular: '<bx_text:_bx_media_uploader_filepond_labelFileCountSingular />',
    labelFileCountPlural: '<bx_text:_bx_media_uploader_filepond_labelFileCountPlural />',
    labelFileLoading: '<bx_text:_bx_media_uploader_filepond_labelFileLoading />',
    labelFileAdded: '<bx_text:_bx_media_uploader_filepond_labelFileAdded />',
    labelFileLoadErro: '<bx_text:_bx_media_uploader_filepond_labelFileLoadError />',
    labelFileRemoved: '<bx_text:_bx_media_uploader_filepond_labelFileRemoved />',
    labelFileRemoveError: '<bx_text:_bx_media_uploader_filepond_labelFileRemoveError />',
    labelFileProcessing: '<bx_text:_bx_media_uploader_filepond_labelFileProcessing />',
    labelFileProcessingComplete: '<bx_text:_bx_media_uploader_filepond_labelFileProcessingComplete />',
    labelFileProcessingAborted: '<bx_text:_bx_media_uploader_filepond_labelFileProcessingAborted />',
    labelFileProcessingError: '<bx_text:_bx_media_uploader_filepond_labelFileProcessingError />',
    labelFileProcessingRevertError: '<bx_text:_bx_media_uploader_filepond_labelFileProcessingRevertError />',
    labelTapToCancel: '<bx_text:_bx_media_uploader_filepond_labelTapToCancel />',
    labelTapToRetry: '<bx_text:_bx_media_uploader_filepond_labelTapToRetry />',
    labelTapToUndo: '<bx_text:_bx_media_uploader_filepond_labelTapToUndo />',
    labelButtonRemoveItem: '<bx_text:_bx_media_uploader_filepond_labelButtonRemoveItem />',
    labelButtonAbortItemLoad: '<bx_text:_bx_media_uploader_filepond_labelButtonAbortItemLoad />',
    labelButtonRetryItemLoad: '<bx_text:_bx_media_uploader_filepond_labelButtonRetryItemLoad />',
    labelButtonAbortItemProcessing: '<bx_text:_bx_media_uploader_filepond_labelButtonAbortItemProcessing />',
    labelButtonUndoItemProcessing: '<bx_text:_bx_media_uploader_filepond_labelButtonUndoItemProcessing />',
    labelButtonRetryItemProcessing: '<bx_text:_bx_media_uploader_filepond_labelButtonRetryItemProcessing />',
    labelButtonProcessItem: '<bx_text:_bx_media_uploader_filepond_labelButtonProcessItem />',
    fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
        if (type != ''){
            aFileType = type.split("/");
            resolve('.'+aFileType[1]);
        }
    })
}
</script>
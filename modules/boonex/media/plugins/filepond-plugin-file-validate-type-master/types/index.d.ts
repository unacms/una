declare module "filepond-plugin-file-validate-type" {
    const FilePondPluginFileValidateType: FilePondPluginFileValidateTypeProps;
    export interface FilePondPluginFileValidateTypeProps {
        /** Enable or disable file type validation. */
        allowFileTypeValidation?: boolean;
        /** Array of accepted file types. Can be mime types or wild cards. For instance ['image/*'] will accept all images. ['image/png', 'image/jpeg'] will only accepts PNGs and JPEGs. */
        acceptedFileTypes?: string[];
        /** Message shown when an invalid file is added. */
        labelFileTypeNotAllowed?: string;
        /** Message shown to indicate the allowed file types. Available placeholders are {allTypes}, {allButLastType}, {lastType}. */
        fileValidateTypeLabelExpectedTypes?: string;
        /** Allows mapping the file type to a more visually appealing label, { 'image/jpeg': '.jpg' } will show .jpg in the expected types label. Set to null to hide a type from the label. */
        fileValidateTypeLabelExpectedTypesMap?: object;
        /** A function that receives a file and the type detected by FilePond, should return a Promise, resolve with detected file type, reject if can’t detect. */
        fileValidateTypeDetectType?: (file: File, type: string) => Promise<string>;
    }
    export default FilePondPluginFileValidateType;
  }
  
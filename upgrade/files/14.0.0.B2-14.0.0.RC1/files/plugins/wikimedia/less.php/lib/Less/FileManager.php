<?php
/**
 * @private
 */
class Less_FileManager {
	/**
	 * Get the full absolute path and uri of the import
	 * @see less-node/FileManager.getPath https://github.com/less/less.js/blob/v2.5.3/lib/less-node/file-manager.js#L70
	 * @param string $filename
	 * @param null|array $currentFileInfo
	 * @return null|array{0:string,1:string}
	 */
	public static function getFilePath( $filename, $currentFileInfo ) {
		if ( !$filename ) {
			return;
		}

			$import_dirs = [];

		if ( Less_Environment::isPathRelative( $filename ) ) {
			// if the path is relative, the file should be in the current directory
			if ( $currentFileInfo ) {
				$import_dirs[ $currentFileInfo['currentDirectory'] ] = $currentFileInfo['uri_root'];
			}

		} else {
			// otherwise, the file should be relative to the server root
			if ( $currentFileInfo ) {
				$import_dirs[ $currentFileInfo['entryPath'] ] = $currentFileInfo['entryUri'];
			}
			// if the user supplied entryPath isn't the actual root
			$import_dirs[ $_SERVER['DOCUMENT_ROOT'] ] = '';

		}

			// always look in user supplied import directories
			$import_dirs = array_merge( $import_dirs, Less_Parser::$options['import_dirs'] );

		foreach ( $import_dirs as $rootpath => $rooturi ) {
			if ( is_callable( $rooturi ) ) {
				$res = $rooturi( $filename );
				if ( $res && is_string( $res[0] ) ) {
					return [
						Less_Environment::normalizePath( $res[0] ),
						Less_Environment::normalizePath( $res[1] ?? dirname( $filename ) )
					];
				}
			} elseif ( !empty( $rootpath ) ) {
				$path = rtrim( $rootpath, '/\\' ) . '/' . ltrim( $filename, '/\\' );
				if ( file_exists( $path ) ) {
					return [
						Less_Environment::normalizePath( $path ),
						Less_Environment::normalizePath( dirname( $rooturi . $filename ) )
					];
				}
				if ( file_exists( $path . '.less' ) ) {
					return [
						Less_Environment::normalizePath( $path . '.less' ),
						Less_Environment::normalizePath( dirname( $rooturi . $filename . '.less' ) )
					];
				}
			}
		}
	}
}

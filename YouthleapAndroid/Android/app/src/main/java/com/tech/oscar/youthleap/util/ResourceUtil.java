package com.tech.oscar.youthleap.util;

import android.app.Activity;
import android.graphics.Bitmap;
import android.hardware.Camera;
import android.os.Environment;
import android.text.TextUtils;
import android.view.Surface;

import com.tech.oscar.youthleap.AppConstant;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;


public class ResourceUtil {
	public static String RES_DIRECTORY = Environment.getExternalStorageDirectory().getAbsolutePath() + "/YouthLeap/";
	/*
	 * Delete File
	 */
	public static boolean DeleteFile(File f) {
		if (f != null && f.exists() && !f.isDirectory()) {
			return f.delete();
		}
		return false;
	}

	public static boolean DeleteFile(String f) {
		if (!TextUtils.isEmpty(f)) {
			return DeleteFile(new File(f));
		}
		return false;
	}

	/*
	 * File
	 */
	public static String getCameraFilePath() {
		String tempFileName = "camera.jpg";

		File tempDir = new File(RES_DIRECTORY);
		if (!tempDir.exists())
			tempDir.mkdirs();
		File tempFile = new File(RES_DIRECTORY + tempFileName);
		if (!tempFile.exists())
			try {
				tempFile.createNewFile();
			} catch (IOException e) {
				e.printStackTrace();
				return null;
			}
		return RES_DIRECTORY + tempFileName;
	}

	public static String getAvatarFilePath() {
		String tempFileName = "avatar.jpg";

		File tempDir = new File(RES_DIRECTORY);
		if (!tempDir.exists())
			tempDir.mkdirs();
		File tempFile = new File(RES_DIRECTORY + tempFileName);
		if (!tempFile.exists())
			try {
				tempFile.createNewFile();
			} catch (IOException e) {
				e.printStackTrace();
				return null;
			}
		return RES_DIRECTORY + tempFileName;
	}

	public static String getDownloadDirectory() {
		File tempDir = new File(RES_DIRECTORY);
		if (!tempDir.exists())
			tempDir.mkdirs();

		String tempDirPath = RES_DIRECTORY + "Donwload/";
		tempDir = new File(tempDirPath);
		if (!tempDir.exists())
			tempDir.mkdirs();

		return tempDirPath;
	}

	public static String getDownloadFilePath(String fileName) {
		return getDownloadDirectory() + fileName + AppConstant.APP_FILE_EXTENSION;
	}

	/*
	 * Post
	 */
	// photo
	private static String mPhotoFileExtension = "jpg";
	public static void setPhotoExtension(String fileExtension) {
		mPhotoFileExtension = fileExtension;
	}
	public static String getCaptureImageFilePath() {
		return RES_DIRECTORY + "post_image."+mPhotoFileExtension;
	}

	// video
	private static String mVideoFileExtension = "mp4";
	public static void setVideoExtension(String fileExtension) {
		mVideoFileExtension = fileExtension;
	}

	public static String getVideoExtension() {
		return mVideoFileExtension;
	}

	public static String getCaptureVideoFilePath() {
		return RES_DIRECTORY + "post_video." + mVideoFileExtension;
	}

	public static String getTrimedVideoFilePath() {
		return RES_DIRECTORY + "post_trimed_video." + mVideoFileExtension;
	}

	public static String getVideoThumbnailFilePath() {
		return RES_DIRECTORY + "video_thumbnail.bmp";
	}

	// audio
	private static String mAudioFileExtension = "wav";
	public static void setAudioExtension(String audioExtension) {
		mAudioFileExtension = audioExtension;
	}
	public static String getAudioExtension() {
		return mAudioFileExtension;
	}

	public static String mAudioName = "";
	public static void setAudioName(String audioName) {
		mAudioName = audioName;
	}
	public static String getAudioName() {
		return mAudioName;
	}

	public static String getCaptureAudioFilePath() {
		return RES_DIRECTORY + "post_audio." + mAudioFileExtension;
	}

	public static String getTrimedAudioFilePath() {
		return RES_DIRECTORY + "post_trimed_audio." + mAudioFileExtension;
	}

	public static int getRotationAngle(Activity mContext, int cameraId) {
		Camera.CameraInfo info = new Camera.CameraInfo();
		Camera.getCameraInfo(cameraId, info);
		int rotation = mContext.getWindowManager().getDefaultDisplay().getRotation();
		int degrees = 0;
		switch (rotation) {
			case Surface.ROTATION_0:
				degrees = 0;
				break;
			case Surface.ROTATION_90:
				degrees = 90;
				break;
			case Surface.ROTATION_180:
				degrees = 180;
				break;
			case Surface.ROTATION_270:
				degrees = 270;
				break;
		}
		int result;
		if (info.facing == Camera.CameraInfo.CAMERA_FACING_FRONT) {
			result = (info.orientation + degrees) % 360;
			result = (360 - result) % 360; // compensate the mirror
		} else { // back-facing
			result = (info.orientation - degrees + 360) % 360;
		}
		return result;
	}
	/*
	 * Copy
	 */
	public static void Copy(File src, File dst) throws IOException {
		InputStream in = new FileInputStream(src);
		OutputStream out = new FileOutputStream(dst);

		// Transfer bytes from in to out
		byte[] buf = new byte[1024];
		int len;
		while ((len = in.read(buf)) > 0) {
			out.write(buf, 0, len);
		}
		in.close();
		out.close();
	}

	/*
	 * Save file to bitmap
	 */
	public static void SaveToBitmap(Bitmap bitmap, String outputFilePath) {
		String filePath = RES_DIRECTORY+outputFilePath;
		if (bitmap == null || TextUtils.isEmpty(filePath))
			return;

		File file = new File(filePath);
		if (!file.exists())
			file.delete();

		// write to bitmap
		FileOutputStream out = null;
		try {
			out = new FileOutputStream(file);
			bitmap.compress(Bitmap.CompressFormat.PNG, 100, out); // bmp is your Bitmap instance
			// PNG is a lossless format, the compression factor (100) is ignored
		} catch (Exception e) {
			e.printStackTrace();

		} finally {
			try {
				if (out != null) {
					out.close();
				}

			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}

	/*
	 * Crop Bitmap
	 */
	public static Bitmap CropBitmap(Bitmap orginBitmap, int startX, int startY, int cropWidth, int cropHeight) {
		Bitmap outputBitmap = Bitmap.createBitmap(orginBitmap, startX, startY, cropWidth, cropHeight);
		return outputBitmap;
	}
}

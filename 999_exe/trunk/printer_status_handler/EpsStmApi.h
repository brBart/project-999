#include <windows.h>
#include <windef.h>

#pragma once

///////////////////////////////////////////////////////////////////////////////
// ERROR CODE :
typedef int SA_RESULT;

#define		SUCCESS						0				// Success
#define		ERR_TYPE					-10				// nType error
#define		ERR_OPENED					-20				// Already opened
#define		ERR_NO_PRINTER				-30				// There is not printer driver
#define		ERR_NO_TARGET				-40				// Printer out of object
#define		ERR_NO_MEMORY				-50				// No memory
#define		ERR_HANDLE					-60				// Invalid handle
#define		ERR_TIMEOUT					-70				// Ttime out
#define		ERR_ACCESS					-80				// cannot read/write
#define		ERR_PARAM					-90				// param error
#define		ERR_NOT_SUPPORT				-100			// not support
#define		ERR_OFFLINE					-110			// now offline
#define		ERR_NOT_EPSON				-120			// not epson printer
#define		ERR_WITHOUT_CB				-130			// without callback function
#define		ERR_BUFFER_OVER_FLOW		-140			// Read buffer over flow
#define		ERR_REGISTRY				-150			// Regstry error
#define		ERR_ENABLE					-160			// BiOpenMonPrinter() is already called
#define		ERR_DISK_FULL				-170			// Capacity of a disk is insufficient
#define		ERR_NO_IMAGE				-180			// No image data
#define		ERR_ENTRY_OVER				-190			// Registration number-of-cases over
#define		ERR_CROPAREAID				-200			// No specific CropAreaID
#define		ERR_EXIST					-210			// Already the same thing
#define		ERR_NOT_FOUND				-220			// Not found
#define		ERR_IMAGE_FILEOPEN			-230			// Open failure
#define		ERR_IMAGE_UNKNOWNFORMAT		-240			// Format injustice
#define		ERR_IMAGE_FAILED			-250			// Image creation failure
#define		ERR_WORKAREA_NO_MEMORY		-260			// No memory for WORKAREA
#define		ERR_WORKAREA_UNKNOWNFORMAT	-270			// Image creation failure because of format injustice
#define		ERR_WORKAREA_FAILED			-280			// WORKAREA creation failure
#define		ERR_IMAGE_FILEREAD			-290			// Image file read error
#define		ERR_PAPERINSERT_TIMEOUT		-300			// Image paper insert error of timeout
#define		ERR_EXEC_FUNCTION			-310			// Other API is running (3.01)
#define		ERR_EXEC_MICR				-320			// Now reading MICR (3.01)
#define		ERR_EXEC_SCAN				-330			// Now scaning image(3.01)
#define		ERR_SS_NOT_EXIST			-340			// Status service not started (3.01)
#define		ERR_PH_NOT_EXIST			ERR_SS_NOT_EXIST	// Status Service is replaced by PH (4.00) 
#define		ERR_SPL_NOT_EXIST			-350			// Spooler service not started (3.01)
#define		ERR_SPL_PAUSED				-370			// Spooler service paused (3.01)
#define		ERR_RESET					-400			// Now printer is reseting (3.01)
#define		ERR_THREAD					-420			// Thread initalization error (3.03)
#define		ERR_ABORT					-430			// BiSCNMICRCancelFunction was called (3.03)
#define		ERR_MICR					-440			// Error occured during MICR handling (3.03)
#define		ERR_SCAN					-450			// Error occured during image scan handling (3.03)
#define		ERR_LINE_OVERFLOW			-460			// Line overflow occured during transaction print (3.03)
#define		ERR_NOT_EXEC				-470			// Required handling not performed (3.03)
#define		ERR_LOCKED					-1000			// Already locked (4.00)

///////////////////////////////////////////////////////////////////////////////
// ASB Bit :
#define		ASB_NO_RESPONSE				0x00000001UL	//No response
#define		ASB_PRINT_SUCCESS			0x00000002UL	//Finish to print
#define		ASB_DRAWER_KICK				0x00000004UL	//Drawer kick-out connector pin3 is HIGH
#define		ASB_BATTERY_OFFLINE			0x00000004UL	//Off-line for BATTERY QUANTITY		
#define		ASB_PRESENTER_COVER			0x00000004UL	//Presenter cover is open
#define		ASB_OFF_LINE				0x00000008UL	//Off-line
#define		ASB_COVER_OPEN				0x00000020UL	//Cover is open
#define		ASB_PLATEN_OPEN				0x00000020UL	//Platen is open
#define		ASB_PRINTER_FEED			0x00000040UL	//Paper is being fed by using the PAPER FEED button
#define		ASB_PAPER_FEED				0x00000040UL	//Paper is now feeding by PF FW (3.01)

#define		ASB_WAIT_ON_LINE			0x00000100UL	//Waiting for on-line recovery
#define		ASB_WAIT_PEPRT_EJECT		0x00000100UL	//Waiting for label insertion (3.03)
#define		ASB_PANEL_SWITCH			0x00000200UL	//Panel switch
#define		ASB_MECHANICAL_ERR			0x00000400UL	//Mechanical error
#define		ASB_RECOVER_ERR				0x00000400UL	//Recoverable error
#define		ASB_LABEL_ERR				0x00000400UL	//LABEL check error
#define		ASB_AUTOCUTTER_ERR			0x00000800UL	//Auto cutter error
#define		ASB_UNRECOVER_ERR			0x00002000UL	//Unrecoverable error
#define		ASB_AUTORECOVER_ERR			0x00004000UL	//Auto-Recoverable error
#define		ASB_HEAD_TEMPERATURE_ERR	0x00004000UL	//Mechanical error

#define		ASB_JOURNAL_NEAR_END		0x00010000UL	//Journal paper roll near-end
#define		ASB_JOURNAL_NEAR_END_FIRST	0x00010000UL	//Journal paper roll near-end-first
#define		ASB_NOT_CARD_INSERT			0x00010000UL	//Card not present at insertion sensor (3.03)
#define		ASB_RECEIPT_NEAR_END		0x00020000UL	//Receipt paper roll near-end
#define		ASB_RECEIPT_NEAR_END_FIRST	0x00020000UL	//Receipt paper roll near-end-first
#define		ASB_JOURNAL_END				0x00040000UL	//Journal paper roll end
#define		ASB_PAPER_END				0x00040000UL	//Detected paper roll end (3.01)
#define		ASB_EJECT_SENSOR_NO_PAPER	0x00040000UL	//No paper at eject sensor (3.03)
#define		ASB_RECEIPT_END				0x00080000UL	//Receipt paper roll end
#define		ASB_SLIP_TOF				0x00200000UL	//SLIP TOF
#define		ASB_SLIP_BOF_2				0x00200000UL	//SLIP BOF 2
#define		ASB_PSUPPLIER_END			0x00200000UL	//Paper supply end
#define		ASB_SLIP_BOF				0x00400000UL	//SLIP BOF
#define		ASB_SLIP_TOF_2				0x00400000UL	//SLIP TOF 2
#define		ASB_RECEIPT_NEAR_END_SECOND	0x00400000UL	//Receipt paper roll near-end-second
#define		ASB_ASF_PAPER				0x00400000UL	//No paper in auto sheet feeder (3.03)

#define		ASB_SLIP_SELECTED			0x01000000UL	//Slip is not selected
#define		ASB_BM_ERR					0x01000000UL	//BM detection error
#define		ASB_WAIT_REMOVE_LABEL		0x01000000UL	//Waiting for label removal
#define		ASB_PRESENTER_TE			0x01000000UL	//Presenter T/E receipt end
#define		ASB_PAPER_SELECTED			0x01000000UL	//Slip roll selected (3.05)
#define		ASB_PRINT_SLIP				0x02000000UL	//Cannot print on slip
#define		ASB_PRESENTER_TT			0x02000000UL	//Presenter T/T receipt end
#define		ASB_SLIP_STATUS				0x02000000UL	//Slip printing impossible/possible (3.05)
#define		ASB_VALIDATION_SELECTED		0x04000000UL	//Validation is not selected
#define		ASB_NO_LABEL				0x04000000UL	//Label peeing sensor: paper not present
#define		ASB_ASF_SELECTED			0x04000000UL	//Auto sheet feeder / manual input selected (3.05)
#define		ASB_RETRACTOR_R1JAM			0x04000000UL	//Retractor receipt end R1JAM 
#define		ASB_PRINT_VALIDATION		0x08000000UL	//Cannot print on validation
#define		ASB_RETRACTOR_BOX			0x08000000UL	//Retractor box
#define		ASB_VALIDATION_TOF			0x20000000UL	//Validation TOF
#define		ASB_RETRACTOR_R2JAM			0x20000000UL	//Retractor receipt end R2JAM
#define		ASB_PAPER_INSWAIT			0x20000000UL	//Paper inerstion wait (3.05)
#define		ASB_WAIT_INSERT				0x20000000UL	//Label insertion wait (3.03)
#define		ASB_VALIDATION_BOF			0x40000000UL	//Validation BOF
#define		ASB_VALIDATION_NO_PAPER		0x40000000UL	//No paper at validation sensor (3.03)
#define		ASB_RETRACTOR_SENSOR3		0x40000000UL	//Retractor sensor NO.3
#define		ASB_RELEASE_OPEN			0x40000000UL	//Release lever open/close (3.05)
#define		ASB_SPOOLER_IS_STOPPED		0x80000000UL	// The spooer is stopped

#define		INK_ASB_NEAR_END			0x0001			//Ink Low
#define		INK_ASB_END					0x0002			//Replace Ink Cartridge
#define		INK_ASB_NO_CARTRIDGE		0x0004			//Cartridge is not present
#define		INK_ASB_NO_CARTRIDGE2		0x0008			//Cartridge2 is not present
#define		INK_ASB_CLEANING			0x0020			//Being cleaned
#define		INK_ASB_NEAR_END2			0x0100			//Ink Low2
#define		INK_ASB_END2				0x0200			//Replace Ink Cartridge2

///////////////////////////////////////////////////////////////////////////////
// BiOpenMonPrinter() :

// Type
#define		TYPE_PORT					1				// use port name
#define		TYPE_PRINTER				2				// use printer driver name

///////////////////////////////////////////////////////////////////////////////
// BiOpenDrawer() :

// Drawer Number
#define		EPS_BI_DRAWER_1				1				// Drawer 1
#define		EPS_BI_DRAWER_2				2				// Drawer 2

// Start Time
#define		EPS_BI_PULSE_100			1				// 100 m sec
#define		EPS_BI_PULSE_200			2				// 200 m sec
#define		EPS_BI_PULSE_300			3				// 300 m sec
#define		EPS_BI_PULSE_400			4				// 400 m sec
#define		EPS_BI_PULSE_500			5				// 500 m sec
#define		EPS_BI_PULSE_600			6				// 600 m sec
#define		EPS_BI_PULSE_700			7				// 700 m sec
#define		EPS_BI_PULSE_800			8				// 800 m sec

///////////////////////////////////////////////////////////////////////////////
// BiSCNSetImageQuality() :

// Scale Option
#define		EPS_BI_SCN_1BIT				1				// Monochrome
#define		EPS_BI_SCN_8BIT				8				// Glay scale

// Color Option
#define		EPS_BI_SCN_MONOCHROME		48				// Monochrome
#define		EPS_BI_SCN_COLOR			49				// Color

// Extensive Option
#define		EPS_BI_SCN_AUTO				48				// Auto
#define		EPS_BI_SCN_MANUAL			49				// Manual
#define		EPS_BI_SCN_SHARP			50				// Sharpness

///////////////////////////////////////////////////////////////////////////////
// BiSCNSetImageFormat() :

// Format Option
#define		EPS_BI_SCN_TIFF				1				// TIFF format(CCITT Group 4)
#define		EPS_BI_SCN_RASTER			2				// Raster Image
#define		EPS_BI_SCN_BITMAP			3				// Bitmap
#define		EPS_BI_SCN_TIFF256			4				// TIFF format(Glay scale)
#define		EPS_BI_SCN_JPEGHIGH			5				// Jpeg format(High complessed)
#define		EPS_BI_SCN_JPEGNORMAL		6				// Jpeg format(Normal)
#define		EPS_BI_SCN_JPEGLOW			7				// Jpeg format(Low complessed)

///////////////////////////////////////////////////////////////////////////////
// BiSCNReadImage() :

// Select Sheet Option
#define		EPS_BI_SCN_JOURNAL			1				// Select Journal
#define		EPS_BI_SCN_RECEIPT			2				// Select Receipt
#define		EPS_BI_SCN_SLIP				4				// Select Slip (3.03)
#define		EPS_BI_SCN_CARD				16				// Select Card
#define		EPS_BI_SCN_CHECKPAPER		32				// Select Check Paper

// Timing Option
#define		EPS_BI_SCN_TRANSMIT_BATCH		48			// Batch mode
#define		EPS_BI_SCN_TRANSMIT_SEQUENCE	49			// Sequence mode

// Memory Option
#define		EPS_BI_SCN_NVMEMORY_NOTSAVE	48				// Not save (save to work area)
#define		EPS_BI_SCN_NVMEMORY_SAVE	49				// Save

///////////////////////////////////////////////////////////////////////////////
// BiSCNSelectScanUnit() :

// Scan unit ID
#define		EPS_BI_SCN_UNIT_CHECKPAPER	48				// Checkpaper scanner unit
#define		EPS_BI_SCN_UNIT_CARD		49				// Card scanner unit

///////////////////////////////////////////////////////////////////////////////
// BiESCNEnable() :

// Saved Method
#define		CROP_STORE_MEMORY			0				// Saved memory
#define		CROP_STORE_FILE				1				// Saved file

///////////////////////////////////////////////////////////////////////////////
// BiESCNSetAutoSize() :

// Enable/Disable Autosize
#define		CROP_AUTOSIZE_DISABLE		0				// Enabled auto size
#define		CROP_AUTOSIZE_ENABLE		1				// Disabled auto size

///////////////////////////////////////////////////////////////////////////////
// BiESCNSetRotate() :

// Enable/Disable Rotate
#define		CROP_ROTATE_DISABLE			0				// Enabled rotate
#define		CROP_ROTATE_ENABLE			1				// Disabled rotate

///////////////////////////////////////////////////////////////////////////////
// BiESCNDefineCropArea() :

// CropArea Setting
#define		CROP_AREA_RESET_ALL			0				// Delete all crop area
#define		CROP_AREA_ENTIRE_IMAGE		1				// CropArea entire image
#define		CROP_AREA_RIGHT				65535			// End X Point
#define		CROP_AREA_BOTTOM			65535			// End Y Point

///////////////////////////////////////////////////////////////////////////////
// BiESCNClearImage() :

// Delete Method
#define		CROP_CLEAR_ALL_IMAGE		0x0000			// Clear all image
#define		CROP_CLEAR_BY_FILEINDEX		0x0001			// Clear by fileIndex
#define		CROP_CLEAR_BY_FILEID		0x0002			// Clear by fileId
#define		CROP_CLEAR_BY_IMAGETAGDATA	0x0004			// Clear by imageTagData

///////////////////////////////////////////////////////////////////////////////
// BiMICRReadCheck() :

// Font
#define MICR_FONT_E13B					0
#define MICR_FONT_CMC7					1

///////////////////////////////////////////////////////////////////////////////
// BiAutoPowerOffTime() :

#define EPS_BI_GET						0
#define EPS_BI_SET						1

///////////////////////////////////////////////////////////////////////////////
// BiGetInkInformation() :
#pragma pack (8) 
typedef struct _STINKINFO{
	BYTE  cInkLevel;									// Ink level(1-100)
	char  szDateMft[5];									// the date of Manufacture(YYMM)
	BYTE  cInkType;										// type of the ink(1:MONO 2:4COLOR)
	char  szModel[32];									// Ink model
	char  szMaker[32];									// Manufacturer
} STINKINFO , *PSTINKINFO;
#pragma pack()

#pragma pack (8) 
///////////////////////////////////////////////////////////////////////////////
// BiSCNMICRFunction() :
// Message ID
#define WM_MF_DONE						(WM_USER +1)
#define WM_MF_PROGRESS					(WM_USER +2)

// wParam
#define MF_MACRO_GETUNITID(wParam)		LOBYTE(HIWORD(wParam))
#define MF_MACRO_GETPHASE(wParam)		LOBYTE(LOWORD(wParam))
#define MF_MACRO_GETFACE(lParam)		LOBYTE(HIWORD(lParam))
#define MF_MACRO_PERCENT(lParam)		LOBYTE(LOWORD(lParam))

#define MF_PHASE_INIT					0
#define MF_PHASE_MICR					1
#define MF_PHASE_SCAN					2
#define MF_PHASE_PRINT					3
#define MF_PHASE_EXIT					4

#define MF_PROGRESS_START				0
#define MF_PROGRESS_DONE				100
#define MF_PROGRESS_WAIT_PAPER			101
#define MF_PROGRESS_CLUMP_PAPER			102
#define MF_PROGRESS_PAPER_PILED			103

// Function define
#define MF_EXEC							0x0000
#define MF_CONTINUE						0x0001
#define MF_MICR_RETRANS					0x0002
#define MF_SCAN_FRONT_RETRANS			0x0003
#define MF_SCAN_BACK_RETRANS			(MF_SCAN_FRONT_RETRANS + 1)

#define MF_SET_BASE_PARAM				0x0010
#define MF_SET_MICR_PARAM				0x0011
#define MF_SET_SCAN_FRONT_PARAM			0x0012
#define MF_SET_SCAN_PARAM				0x0012
#define MF_SET_SCAN_BACK_PARAM			(MF_SET_SCAN_FRONT_PARAM + 1)
#define MF_SET_PRINT_PARAM				0x0014

#define MF_CLEAR_BASE_PARAM				0x0020
#define MF_CLEAR_MICR_PARAM				0x0021
#define MF_CLEAR_SCAN_FRONT_PARAM		0x0022
#define MF_CLEAR_SCAN_PARAM				0x0022
#define MF_CLEAR_SCAN_BACK_PARAM		(MF_CLEAR_SCAN_FRONT_PARAM + 1)
#define MF_CLEAR_PRINT_PARAM			0x0024

#define	MF_GET_BASE_DEFAULT				0x0030
#define	MF_GET_MICR_DEFAULT				0x0031
#define	MF_GET_SCAN_DEFAULT				0x0032
#define	MF_GET_SCAN_FRONT_DEFAULT		0x0032
#define	MF_GET_SCAN_BACK_DEFAULT		(MF_GET_SCAN_FRONT_DEFAULT + 1)
#define	MF_GET_PRINT_DEFAULT			0x0034

/**********************/
/* Base parameters    */
/**********************/
// Version define
#define MF_FIRST_VERSION				0x0100
#define MF_BASE_VERSION01				0x0101
#define MF_BASE_VERSION					0x0100

// Message method
#define MF_BASE_MESSAGE_NO_MESSAGE		1
#define MF_BASE_MESSAGE_EVENT			2
#define MF_BASE_MESSAGE_HWND			3
#define MF_BASE_MESSAGE_BUTTON_CLICK	4
#define MF_BASE_MESSAGE_NOTIFY_MAX		MF_BASE_MESSAGE_BUTTON_CLICK

#define MF_BASE_TIMEOUT_MAX				300		// 5 min
#define MF_BASE_TIMEOUT_DEFAULT			0		// INFINIT

// NV Memory
#define MF_BASE_NVMEMORY_NOT_USE		0
#define MF_BASE_NVMEMORY_USE			1

// Eject Type when error occured
#define	MF_EJECT_DISCHARGE			0x0000
#define	MF_EJECT_RELEASE			0x0001
#define	MF_EXIT_ERROR_DISCHARGE		MF_EJECT_DISCHARGE
#define	MF_EXIT_ERROR_RELEASE		MF_EJECT_RELEASE
#define	MF_EXIT_SUCCESS_DISCHARGE	MF_EJECT_DISCHARGE
#define	MF_EXIT_SUCCESS_RELEASE		MF_EJECT_RELEASE
#define	MF_EXIT_ERROR_CONTINUE_DISCHARGE	0x0010
#define	MF_EXIT_ERROR_CONTINUE_RELEASE		0x0011

#define	MF_BUZZER_TYPE_SUCCESS		0
#define	MF_BUZZER_TYPE_ERROR		1
#define	MF_BUZZER_TYPE_WFEED		2
#define	MF_BUZZER_TYPE_MAX			3

#define	MF_BUZZER_HZ_4000			0
#define	MF_BUZZER_HZ_440			1
#define	MF_BUZZER_HZ_880			2

#define	MF_BUZZER_DISABLE			0
#define	MF_BUZZER_COUNT_MAX			3

// Structure (Version 0x0101)
typedef struct 
{
	int iSize;
	int iVersion;
	int iRet;
	DWORD dwNotifyType;
	DWORD dwTimeout;
	union {
		LPHANDLE lphNotifyEvent;
		HWND hNotifyWnd;
	} uNotifyHandle;
	HWND hProgressWnd;
	WORD wErrorEject;
	BYTE bBuzzerHz[MF_BUZZER_TYPE_MAX];
	BYTE bBuzzerCount[MF_BUZZER_TYPE_MAX];
	BYTE bUseNVMemory;
	char cPortName[256];
	WORD wSuccessEject;
} MF_BASE01, *LPMF_BASE01;

// Structure (OLD:Version 0x0100)
typedef struct 
{
	int iSize;
	int iVersion;
	int iRet;
	DWORD dwNotifyType;
	DWORD dwTimeout;
	union {
		LPHANDLE lphNotifyEvent;
		HWND hNotifyWnd;
	} uNotifyHandle;
	HWND hProgressWnd;
	WORD wErrorEject;
	BYTE bBuzzerHz[MF_BUZZER_TYPE_MAX];
	BYTE bBuzzerCount[MF_BUZZER_TYPE_MAX];
	BYTE bUseNVMemory;
	char cPortName[256];
} MF_BASE, *LPMF_BASE;

/**********************/
/* MICR parameters    */
/**********************/
// Version define
#define MF_MICR_VERSION					0x0100

// MICR type
#define MF_MICR_USE_MICR				1
#define MF_MICR_USE_OCR					2

// Font
#define MF_MICR_FONT_E13B				0
#define MF_MICR_FONT_CMC7				1

#define MF_MICR_CHAR_MAX				81
#define MF_MICR_CHAR_LEN				80

// Structure
typedef struct
{
	CHAR cRecogChar;					// recognized char
	LONG lPercentage;					// reliability(%)
} MF_OCR_RELIABILITY;

typedef struct
{
	LONG				lPosition;		// position
	MF_OCR_RELIABILITY	stFirstSelect;	// 1st candidate
	MF_OCR_RELIABILITY	stSecondSelect;	// 2nd candidate
} MF_OCR_RELIABLE_INFO;

typedef struct
{
	int iSize;
	int iVersion;
	int iRet;
	BYTE bFont;
	BYTE bMicOcrSelect;
	BOOL blParsing;

	BYTE	bStatus;
	BYTE	bDetail;
	CHAR szMicrStr[MF_MICR_CHAR_MAX];
	MF_OCR_RELIABLE_INFO stOcrReliableInfo[MF_MICR_CHAR_MAX];
	CHAR	szAccountNumber[MF_MICR_CHAR_MAX];
	CHAR	szAmount[MF_MICR_CHAR_MAX];
	CHAR	szBankNumber[MF_MICR_CHAR_MAX];
	CHAR	szSerialNumber[MF_MICR_CHAR_MAX];
	CHAR	szEPC[MF_MICR_CHAR_MAX];
	CHAR	szTransitNumber[MF_MICR_CHAR_MAX];
	long	lCheckType;
	long	lCountryCode;
} MF_MICR, *LPMF_MICR;

/**********************/
/* Scan parameters    */
/**********************/
// Version define
#define MF_SCAN_VERSION					0x0100

// Scan face
#define MF_SCAN_FACE_FRONT				0
#define MF_SCAN_FACE_BACK				1
#define MF_SCAN_FACE_BOTH				2

#define MF_SCAN_DPI_DEFAULT				0
#define MF_SCAN_DPI_100					100
#define MF_SCAN_DPI_200					200

typedef struct
{
	int iSize;
	int iVersion;
	int iRet;
	WORD wImageID;
	short sResolution;
	BYTE bAddInfoDataSize;
	LPBYTE pAddInfoData;
	BYTE bStatus;
	BYTE bDetail;
	DWORD dwXSize;
	DWORD dwYSize;
	DWORD dwScanSize;
	LPBYTE lpbScanData;
} MF_SCAN, *LPMF_SCAN;

/**********************/
/* Print parameters   */
/**********************/
// Version define
#define MF_PRINT_VERSION01				0x0101
#define MF_PRINT_VERSION				0x0100

// Line
#define MF_PRINT_LINE_1					0
#define MF_PRINT_LINE_2					1
#define MF_PRINT_LINE_3					2
#define MF_PRINT_LINE_MAX				MF_PRINT_LINE_3

// Charactor attribute
#define MF_PRINT_NO_ATTRIBUTE			0x00000000
#define MF_PRINT_BOLD					0x00000001
#define MF_PRINT_UNDERLINE_1			0x00000010
#define MF_PRINT_UNDERLINE_2			0x00000020
#define MF_PRINT_BLACK					0x00000100
#define MF_PRINT_1ST_COLOR				0x00000100
#define MF_PRINT_COLOR					0x00000200
#define MF_PRINT_2ND_COLOR				0x00000200
#define MF_PRINT_MIXED					0x00000300

// Font type
#define MF_PRINT_FONT_A					1
#define MF_PRINT_FONT_B					2

// Font size
#define MF_PRINT_FONT_W1_H1				0x0011
#define MF_PRINT_FONT_W1_H2				0x0012
#define MF_PRINT_FONT_W2_H1				0x0021
#define MF_PRINT_FONT_W2_H2				0x0022

// Printing speed
#define	MF_PRINT_SPEED_NORMAL			0
#define	MF_PRINT_SPEED_HIGH				1
#define	MF_PRINT_SPEED_ECONOMY			2

// Other
#define MF_PRINT_DERECTION_DOUBLE		0
#define MF_PRINT_DERECTION_SINGLE		1

// EndorseType
#define MF_PRINT_TYPE_ENDORSE_ONLY			0
#define MF_PRINT_TYPE_ENDORSE_NORMAL		1
#define MF_PRINT_TYPE_ELECTRIC_ENDORSE_ONLY	2

// Structure (Version 0x0101)
typedef struct
{
	int iSize;
	int iVersion;
	int iRet;
	BOOL blDummy;
	LPSTR lpString[3];
	DWORD dwAttribute[3];
	WORD wFont[3];
	WORD wFontSize[3];
	BYTE bSpeed;
	BOOL bDirection;
	DWORD dwEndorseType;
} MF_PRINT01, *LPMF_PRINT01;

// Structure (Version 0x0100)
typedef struct
{
	int iSize;
	int iVersion;
	int iRet;
	BOOL blElectricEndorse;
	LPSTR lpString[3];
	DWORD dwAttribute[3];
	WORD wFont[3];
	WORD wFontSize[3];
	BYTE bSpeed;
	BOOL bDirection;
} MF_PRINT, *LPMF_PRINT;

#define	EPSSTMAPI	extern "C"

//Standard
typedef SA_RESULT (WINAPI* DLL_BiOpenMonPrinter)(int, LPSTR);
typedef SA_RESULT (WINAPI* DLL_BiCloseMonPrinter)(int);
typedef SA_RESULT (WINAPI* DLL_BiLockPrinter)(int, DWORD);															// (4.00)
typedef SA_RESULT (WINAPI* DLL_BiUnlockPrinter)(int);																// (4.00)
typedef SA_RESULT (WINAPI* DLL_BiSetMonInterval)(int, WORD, WORD);
typedef SA_RESULT (WINAPI* DLL_BiSetMonEtherInterval)(int, WORD);
typedef SA_RESULT (WINAPI* DLL_BiDirectIO)(int, BYTE, LPBYTE, LPBYTE, LPBYTE, DWORD, BOOL);
typedef SA_RESULT (WINAPI* DLL_BiDirectIOEx)(int, DWORD, LPBYTE, LPDWORD, LPBYTE, DWORD, BOOL, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiResetPrinter)(int);
typedef SA_RESULT (WINAPI* DLL_BiCancelError)(int);
typedef SA_RESULT (WINAPI* DLL_BiGetType)(int, LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiGetOfflineCode)( int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiGetStatus)(int, LPDWORD);
typedef SA_RESULT (WINAPI* DLL_BiGetRealStatus)(int, LPDWORD);														// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSetStatusBackFunction)(int, int (CALLBACK *pStatusCB)(DWORD));
typedef SA_RESULT (WINAPI* DLL_BiSetStatusBackFunctionEx)(int, int (CALLBACK *pStatusCB)(DWORD, LPSTR));			// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSetStatusBackWnd)(int, long, LPDWORD);
typedef SA_RESULT (WINAPI* DLL_BiCancelStatusBack)(int);

//Ink
typedef SA_RESULT (WINAPI* DLL_BiGetInkStatus)(int, LPWORD);
typedef SA_RESULT (WINAPI* DLL_BiGetInkInformation)(int, PSTINKINFO);												// (3.02)
typedef SA_RESULT (WINAPI* DLL_BiSetInkStatusBackFunction)(int, int (CALLBACK *pInkCB)(WORD));
typedef SA_RESULT (WINAPI* DLL_BiSetInkStatusBackFunctionEx)(int, int (CALLBACK *pStatusCB)(WORD, LPSTR));			// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSetInkStatusBackWnd)(int, long, LPDWORD);
typedef SA_RESULT (WINAPI* DLL_BiCancelInkStatusBack)(int);

//Battery
typedef SA_RESULT (WINAPI* DLL_BiGetBatteryStatus)(int, LPBYTE, LPBYTE);											// (3.01)
typedef SA_RESULT (WINAPI* DLL_BiSetBatteryStatusBackFunctionEx)(int, int (CALLBACK *pBattCB)(BYTE, BYTE, LPSTR));	// (3.01)
typedef SA_RESULT (WINAPI* DLL_BiSetBatteryStatusBackWndEx)(int, long, LPBYTE, LPBYTE, LPSTR);						// (3.01)
typedef SA_RESULT (WINAPI* DLL_BiCancelBatteryStatusBack)(int);														// (3.01)

//Presenter
typedef SA_RESULT (WINAPI* DLL_BiSetPresenterEventBackFunction)(int, int (CALLBACK *pEventCB)(BYTE));
typedef SA_RESULT (WINAPI* DLL_BiSetPresenterEventBackFunctionEx)(int, int (CALLBACK *pEventCB)(BYTE, LPSTR));		// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSetPresenterEventBackWnd)(int, long, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiCancelPresenterEventBack)(int);

typedef SA_RESULT (WINAPI* DLL_BiAutoPowerOffTime)(int, BYTE, LPBYTE);												// (3.01)
typedef SA_RESULT (WINAPI* DLL_BiPowerOff)(int);																	// (3.01)
typedef SA_RESULT (WINAPI* DLL_BiCheckPrint)(int, LPBYTE);															// not supported
typedef SA_RESULT (WINAPI* DLL_BiEjectSheet)(int);
typedef SA_RESULT (WINAPI* DLL_BiGetCounter)(int, WORD, LPDWORD);
typedef SA_RESULT (WINAPI* DLL_BiResetCounter)(int, WORD);
typedef SA_RESULT (WINAPI* DLL_BiGetPrnCapability)(int, BYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiOpenDrawer)(int, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiPreparePaperChange)(int);
typedef SA_RESULT (WINAPI* DLL_BiRetractSheet)(int);
typedef SA_RESULT (WINAPI* DLL_BiSendDataFile)(int, LPCSTR);														// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiDirectSendRead)(int, LPCSTR, LPCSTR, LPDWORD, LPBYTE, DWORD, BOOL);				// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSetDefaultEchoTime)(BYTE, WORD);
typedef SA_RESULT (WINAPI* DLL_BiSetEtherEchoTime)(int, BYTE, WORD);
typedef SA_RESULT (WINAPI* DLL_BiSetReadWaitTimeOut)(int, WORD);													// not supported

//MICR API
typedef SA_RESULT (WINAPI* DLL_BiMICRCleaning)(int);
typedef SA_RESULT (WINAPI* DLL_BiMICRGetStatus)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiMICRLoadCheck)(int);
typedef SA_RESULT (WINAPI* DLL_BiMICRReadCheck)(int, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiMICREjectCheck)(int);
typedef SA_RESULT (WINAPI* DLL_BiMICRCancelWaitCheckInsertion)(int);
typedef SA_RESULT (WINAPI* DLL_BiMICRRetransmissionCheckData)(int, LPBYTE, LPBYTE, LPBYTE, LPBYTE, DWORD);
typedef SA_RESULT (WINAPI* DLL_BiMICRSelectDataHandling)(int, BYTE, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiMICRSetReadBackFunction)(int, int (CALLBACK *pMicrCB)(void), LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiMICRSetReadBackFunctionEx)(int, int (CALLBACK *pMicrCB)(LPSTR lpPortName), LPBYTE, LPBYTE, LPBYTE, LPBYTE pDetail);// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiMICRSetReadBackWnd)(int, long, LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiMICRCancelReadBack)(int);

//Scanner API
typedef SA_RESULT (WINAPI* DLL_BiSCNClumpPaper)(int);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetClumpStatus)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetCroppingArea)(int, LPWORD, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNSetCroppingArea)(int, BYTE, BYTE, BYTE, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNDeleteCroppingArea)(int, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetImageFormat)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNSetImageFormat)(int, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetImageList)(int, LPWORD, LPWORD);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetImageQuality)(int, LPBYTE, char *, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNSetImageQuality)(int, BYTE, char, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetImageRemainingCapacity)(int, LPDWORD);
typedef SA_RESULT (WINAPI* DLL_BiSCNGetScanArea)(int, LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNSetScanArea)(int, BYTE, BYTE, BYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNPreScan)(int, LPBYTE, char *);
typedef SA_RESULT (WINAPI* DLL_BiSCNReadImage)(int, WORD, BYTE, BYTE, BYTE, LPBYTE, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNRetransmissionImage)(int, WORD, LPDWORD, LPBYTE, LPBYTE, LPBYTE, LPBYTE, DWORD);
typedef SA_RESULT (WINAPI* DLL_BiSCNDeleteImage)(int, WORD);
typedef SA_RESULT (WINAPI* DLL_BiSCNSelectScanFace)(int, BYTE);														// not supported	// (3.03)
typedef SA_RESULT (WINAPI* DLL_BiSCNSelectScanUnit)(int, BYTE);														// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSCNSetReadBackFunction)(int, int (CALLBACK *pScanCB)(void), LPDWORD, LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNSetReadBackFunctionEx)(int, int (CALLBACK *pScnCB)(LPSTR lpPortName), LPDWORD, LPBYTE, LPBYTE, LPBYTE, LPBYTE);// (3.00)
typedef SA_RESULT (WINAPI* DLL_BiSCNSetReadBackWnd)(int, long, LPDWORD, LPBYTE, LPBYTE, LPBYTE, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiSCNCancelReadBack)(int);

typedef SA_RESULT (WINAPI* DLL_BiSCNMICRCancelFunction)(int, WORD);													// not supported	// (3.03)
typedef SA_RESULT (WINAPI* DLL_BiSCNMICRFunction)(int, LPVOID, WORD);												// not supported	// (3.03)

//Extended scanner API
typedef SA_RESULT (WINAPI* DLL_BiESCNEnable)(BYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetAutoSize)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNSetAutoSize)(int, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetCutSize)(int, LPWORD);
typedef SA_RESULT (WINAPI* DLL_BiESCNSetCutSize)(int, WORD);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetRotate)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNSetRotate)(int, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetDocumentSize)(int, LPWORD, LPWORD);
typedef SA_RESULT (WINAPI* DLL_BiESCNSetDocumentSize)(int, WORD, WORD);
typedef SA_RESULT (WINAPI* DLL_BiESCNDefineCropArea)(int, BYTE, WORD, WORD, WORD, WORD);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetMaxCropAreas)(int, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNStoreImage)(int, DWORD, LPSTR, LPSTR, BYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNRetrieveImage)(int, DWORD, LPSTR, LPSTR, LPDWORD, LPBYTE);
typedef SA_RESULT (WINAPI* DLL_BiESCNClearImage)(int, BYTE, DWORD, LPSTR, LPSTR);
typedef SA_RESULT (WINAPI* DLL_BiESCNGetRemainingImages)(int, LPBYTE);

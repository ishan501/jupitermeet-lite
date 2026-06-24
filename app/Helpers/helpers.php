<?php

use App\Models\AddonSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Language;
use App\Models\Page;
use App\Models\Plan;
use App\Models\User;

//get languages
function getLanguages()
{
	$languages = Cache::rememberForever('languages', function () {
		return Language::where(['status' => 'active'])->select('code', 'name', 'default', 'direction')->get();
	});

	return $languages;
}

//get selected language
function getSelectedLanguage()
{
	if (session('locale')) {
		$selectedLanguage = getLanguages()->first(function ($langauage) {
			return $langauage->code == session('locale');
		});

		if ($selectedLanguage)
			return $selectedLanguage;
	}

	return getDefaultLanguage();
}

//get default language
function getDefaultLanguage()
{
	$languages = Cache::rememberForever('defaultLangauage', function () {
		return Language::where(['default' => 'yes'])->select('code', 'name', 'direction')->first();
	});

	return $languages;
}

//get auth user info
function getAuthUserInfo($property)
{
	return auth()->user() ? auth()->user()->$property : '';
}

//get settings from the global config table
function getSetting($key)
{
	$settings = Cache::rememberForever('settings', function () {
		return Setting::all()->pluck('value', 'key');
	});

	if (!$settings[$key]) {
		Cache::forget('settings');
		$settings = Setting::all()->pluck('value', 'key');
	}

	return $settings[$key];
}

//get addon settings from the addon_settings table
function getAddonSetting($key)
{
	$settings = Cache::rememberForever('addon_settings', function () {
		return AddonSetting::all()->pluck('value', 'key');
	});

	if (!isset($settings[$key])) {
		Cache::forget('addon_settings');

		$settings = AddonSetting::all()->pluck('value', 'key');

		return $settings[$key] ?? null;
	}

	return $settings[$key];
}

//get features associated with the user ID
function getUserPlanFeatures($id)
{
	$user = User::find($id);
	$planId = $user->plan_id;

	if ($user->plan_ends_at == '') {
		$planId = $user->plan_id;
	} else if (date('Y-m-d', strtotime($user->plan_ends_at)) < date('Y-m-d')) {
		$planId = 1;
	}

	return Plan::find($planId)->features;
}

//get pages to show in footer
function getPages()
{
	return Page::select('title', 'slug')->where('footer', 'yes')->get();
}

//format date
function formatDate($date)
{
	return $date ? date('d-m-Y', strtotime($date)) : '';
}

//format time
function formatTime($time)
{
	return $time ? date('h:i A', strtotime($time)) : '';
}

// format money by currency
function formatMoney($amount, $currency)
{
	if (in_array(strtoupper($currency), config('currencies.zero_decimals'))) {
		return number_format($amount, 0, __('.'), __(','));
	} else {
		return number_format($amount, 2, __('.'), __(','));
	}
}

// calculate discount
function calculateDiscount($amount, $discount)
{
	return $amount * ($discount / 100);
}

function callCurlApiRequest($endpoint, $method, $params = null, $gateway = 'paystack')
{
	try {
		if ($gateway == 'paystack') {
			$ApiUrl = "https://api.paystack.co";
			$Secret = getSetting('PAYSTACK_SECRET_KEY');
		} elseif ($gateway == 'razorpay') {
			$ApiUrl = "https://api.razorpay.com/v1";
			$Key = getSetting('RAZORPAY_API_KEY');
			$Secret = getSetting('RAZORPAY_SECRET_KEY');
			$base64encode = base64_encode($Key . ":" . $Secret);
		} else {
			$ApiUrl = "https://api.mollie.com";
			$Secret = getSetting('MOLLIE_API_KEY');
		}

		$ch = curl_init();
		if ($method == 'GET') {
			if ($gateway == 'razorpay') {
				curl_setopt_array($ch, [
					CURLOPT_URL => $ApiUrl . $endpoint,
					CURLOPT_HTTPHEADER => [
						"Authorization: Basic {$base64encode}",
						"Cache-Control: no-cache",
					],
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
				]);
			} else {
				curl_setopt_array($ch, [
					CURLOPT_URL => $ApiUrl . $endpoint,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => [
						"Authorization: Bearer {$Secret}",
						"Cache-Control: no-cache",
					],
				]);
			}
		} else {
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $ApiUrl . $endpoint);
			if ($method == 'PUT') {
				$fields_string = http_build_query($params);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			} elseif ($method == 'PATCH') {
				$fields_string = http_build_query($params);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			} elseif ($method == 'DELETE') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			} else {
				$fields_string = http_build_query($params);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			}

			if ($gateway == 'razorpay') {
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					"Authorization: Basic {$base64encode}",
					"Cache-Control: no-cache",
				]);
			} else {
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					"Authorization: Bearer {$Secret}",
					"Cache-Control: no-cache",
				]);
			}

			//So that curl_exec returns the contents of the cURL; rather than echoing it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		}

		$response = curl_exec($ch);
		$response_array = json_decode($response, true);
		return $response_array;
	} catch (\Exception $e) {
		return back()->with('error', $e->getMessage());
	}
}

// get all payment gateways which are enabled
function paymentGateways()
{
	$paymentGateways = config('payment.gateways');
	foreach ($paymentGateways as $key => $value) {
		if (!getSetting($key)) {
			unset($paymentGateways[$key]);
		}
	}

	return $paymentGateways;
}

/**
 * Calculate the total, including the exclusive taxes.
 * PostDiscount + ExclusiveTax$
 */
function checkoutTotal($amount, $discount, $exclusiveTaxRates, $inclusiveTaxRates)
{
	return calculatePostDiscount($amount, $discount) + (calculatePostDiscount($amount, $discount) * ($exclusiveTaxRates / 100));
}

/**
 * Returns the amount after discount.
 * Amount - Discount$
 */
function calculatePostDiscount($amount, $discount)
{
	return $amount - calculateDiscount($amount, $discount);
}

// export data to CSV
function exportCSV($fileName, $data)
{
	$headers = [
		"Content-Type" => "text/csv; charset=UTF-8",
		"Content-Disposition" => "attachment; filename=$fileName",
		"Pragma" => "no-cache",
		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		"Expires" => "0",
	];

	$callback = function () use ($data) {
		$file = fopen('php://output', 'w');

		fputs($file, "\xEF\xBB\xBF");

		fputcsv($file, array_keys($data[0]));

		foreach ($data as $row) {
			fputcsv($file, (array) $row);
		}

		fclose($file);
	};

	ob_end_clean();
	return response()->streamDownload($callback, $fileName, $headers);
}

// calculate exclusive tax
function checkoutExclusiveTax($amount, $discount, $exclusiveTaxRate, $inclusiveTaxRates)
{
	return calculatePostDiscount($amount, $discount) * ($exclusiveTaxRate / 100);
}

// calculate inclusive tax
function calculateInclusiveTax($amount, $discount, $inclusiveTaxRate, $inclusiveTaxRates)
{
	return calculatePostDiscount($amount, $discount) * ($inclusiveTaxRate / 100);
}

// get current application version 
function getVersion()
{
	return str_replace('.', '', getSetting('VERSION'));
}

//check if the upgrade button should be displayed or not
function showUpgrade()
{
	$user = auth()->user();
	if (!$user) {
		return false;
	}

	$totalActivePlans = Plan::where(['status' => 1])->count();
	$userCurrentPlan = $user->plan_id;

	return count(paymentGateways()) != 0 && getSetting('PAYMENT_MODE') == 'enabled' && $userCurrentPlan < $totalActivePlans;
}

// get theme from session or default setting
function getThemeFromSession()
{
	return session()->get('theme') ?? getSetting('DEFAULT_THEME');
}

function convertToTimezone($datetime)
{
	return Carbon::parse($datetime)->timezone(getSetting('ADMIN_TIMEZONE'));
}

// get the user's IP address
function getVisIpAddr()
{
	if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
		// Get the IP address and port (if present) from the header
		$ipWithPort = $_SERVER['HTTP_X_REAL_IP'];
		// Explode the value by ':' to separate the IP and port, and return only the IP part
		$ipParts = explode(':', $ipWithPort);
		return $ipParts[0];
	} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if (!empty($_SERVER['REMOTE_ADDR'])) {
		return $_SERVER['REMOTE_ADDR'];
	} else {
		return 'Unknown';
	}
}

// get country by IP address using geoplugin
function getCountryByIp($ip)
{
	return @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
}

// get the status of the signaling server
function getSignalingServerStatus()
{
	try {
		get_headers(getSetting('SIGNALING_URL'));
		return true;
	} catch (\Exception $e) {
		return false;
	}
}

function getAiChatbotUrl($chatbotname)
{
	$aiChatbotUrl = [
		'ChatGPT' => "https://api.openai.com/v1",
		'DeepSeek' => 'https://api.deepseek.com/v1',
		'Gemini' => 'https://generativelanguage.googleapis.com/v1beta',
		'Perplexity' => 'https://api.perplexity.ai',
		'Grok' => 'https://api.x.ai/v1'
	];

	return $aiChatbotUrl[$chatbotname];
}
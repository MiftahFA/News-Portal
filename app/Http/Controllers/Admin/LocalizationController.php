<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LocalizationController extends Controller
{
  function adminIndex()
  {
    $languages = Language::all();
    return view('admin.localization.admin-index', compact('languages'));
  }

  function frontnedIndex()
  {
    $languages = Language::all();
    return view('admin.localization.frontend-index', compact('languages'));
  }

  function extractLocalizationStrings(Request $request)
  {
    $directories = explode(',', $request->directory);
    $languageCode = $request->language_code;
    $fileName = $request->file_name;
    $localizationStrings = [];

    foreach ($directories as $directory) {
      $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(trim($directory)));

      foreach ($files as $file) {
        if ($file->isDir()) {
          continue;
        }

        $contents = file_get_contents($file->getPathname());

        preg_match_all('/__\([\'"](.+?)[\'"]\)/', $contents, $matches);

        if (!empty($matches[1])) {
          foreach ($matches[1] as $match) {
            $match = preg_replace('/^(frontend|admin)\./', '', $match);
            $localizationStrings[$match] = $match;
          }
        }
      }
    }

    $phpArray = "<?php\n\nreturn " . var_export($localizationStrings, true) . ";\n";

    if (!File::isDirectory(lang_path($languageCode))) {
      File::makeDirectory(lang_path($languageCode), 0755, true);
    }

    file_put_contents(lang_path($languageCode . '/' . $fileName . '.php'), $phpArray);

    toast(__('admin.Generated Successfully'), 'success');
    return redirect()->back();
  }

  function updateLangString(Request $request)
  {
    $languageStrings = trans($request->file_name, [], $request->lang_code);
    $languageStrings[$request->key] = $request->value;

    $phpArray = "<?php\n\nreturn " . var_export($languageStrings, true) . ";\n";

    file_put_contents(lang_path($request->lang_code . '/' . $request->file_name . '.php'), $phpArray);

    toast(__('admin.Updated Successfully'), 'success');
    return redirect()->back();
  }

  function translateString(Request $request)
  {
    try {
      $languageCode = $request->language_code;
      $languageStrings = trans($request->file_name, [], $languageCode);
      $keyStrings = array_keys($languageStrings);
      $collection = collect($keyStrings);
      $chunks = $collection->chunk(25)->toArray();
      $translatedValues = [];

      foreach ($chunks as $chunk) {
        $text = implode('|', $chunk);
        $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'x-rapidapi-host' => getSetting('site_microsoft_api_host'),
          'x-rapidapi-key' => getSetting('site_microsoft_api_key'),
        ])->post("https://microsoft-translator-text-api3.p.rapidapi.com/largetranslate?to={$languageCode}&from=en", [
          'sep' => '|',
          'text' => $text
        ]);

        if (!$response->successful()) {
          return response(['status' => 'error', 'message' => $response['message']]);
        }

        $translatedText = json_decode($response->body())->text;
        $translatedValues = array_merge($translatedValues, explode('|', $translatedText));
      }

      $updatedArray = array_combine($keyStrings, $translatedValues);

      if (!$updatedArray) {
        return response(['status' => 'error', 'message' => __('admin.Failed to combine keys and values.')]);
      }

      $phpArray = "<?php\n\nreturn " . var_export($updatedArray, true) . ";\n";

      if (!File::isDirectory(lang_path($languageCode))) {
        File::makeDirectory(lang_path($languageCode), 0755, true);
      }

      file_put_contents(lang_path($languageCode . '/' . $request->file_name . '.php'), $phpArray);

      return response(['status' => 'success', 'message' => __('admin.Translation is completed')]);
    } catch (\Throwable $th) {
      return response(['status' => 'error', 'message' => $th->getMessage()]);
    }
  }
}

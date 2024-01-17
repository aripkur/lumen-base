<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Exceptions\CaptchaException;
use Illuminate\Support\Facades\File;

class Captcha
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function path($file = ''){
        return storage_path("app/captcha/" . $file);
    }

    public function generate()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if($this->config['uppercase']){
            $characters = strtoupper($characters);
        }
        $captchaText = '';

        for ($i = 0; $i < $this->config['length']; $i++) {
            $captchaText .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $this->generateImage($captchaText);
    }

    protected function generateImage($captchaText)
    {
        $image = imagecreatetruecolor($this->config['width'], $this->config['height']);
        if($image === false){
            throw new CaptchaException("failed to make image");
        }
        $bgColor = imagecolorallocate($image, 204, 204, 204);
        if($bgColor === false){
            throw new CaptchaException("failed to make generate color for background image");
        }
        if(imagefill($image, 0, 0, $bgColor) === false){
            throw new CaptchaException("failed to make background image");
        }
        $textColor = imagecolorallocate($image, 0, 0, 0);
        if($textColor === false){
            throw new CaptchaException("failed to make text color image");
        }

        $fontPath = storage_path('app/captcha/font/RubikDoodleTriangles.ttf');

        // Menghitung lebar teks
        $textWidth = imagettfbbox($this->config['font_size'], 0, $fontPath, $captchaText);
        if($textWidth === false){
            throw new CaptchaException("failed to make box image");
        }

        // Menempatkan teks di tengah gambar
        $textWidth = $textWidth[2] - $textWidth[0];
        $x = ($this->config['width'] - $textWidth) / 2;
        $y = 30;

        // membuat image text
        $r = imagettftext($image, $this->config['font_size'], 2, $x, $y, $textColor, $fontPath, $captchaText);
        if($r === false){
            throw new CaptchaException("failed to write text to image");
        }
        // membuat noise pada image
        if($this->config['noice']){
            $noiseLevel = 10;
            for ($i = 0; $i < $noiseLevel; $i++) {
                $noiseColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
                if($noiseColor === false){
                    throw new CaptchaException("failed to make color for noice image");
                }
                $ellipseWidth = 25; // Lebar ellips
                $ellipseHeight = 25; // Tinggi ellips
                $r = imageellipse($image, rand(0, $this->config['width']), rand(0, $this->config['height']), $ellipseWidth, $ellipseHeight, $noiseColor);
                if($r === false){
                    throw new CaptchaException("failed to make noice image");
                }
            }
        }

        // Output gambar
        ob_start();
        if(imagepng($image) === false){
            throw new CaptchaException("failed to generate image png");
        }
        $imageData = ob_get_clean();

        if($imageData === false){
            throw new CaptchaException("failed to get buffer image");
        }

        if(imagedestroy($image) === false){
            throw new CaptchaException("failed to destroy image");
        }

        $data = [
            "id" => Carbon::now()->addMinutes($this->config['ttl'])->timestamp,
            "file" => 'data:image/png;base64,' . base64_encode($imageData),
            "expired" => $this->config['ttl'] * 60,
        ];

        $this->save($data['id'], $captchaText);

        return $data;
    }

    public function save($fileId, $captchaText){
        if (!File::isDirectory($this->path())) {
            File::makeDirectory($this->path());
        }

        $filePath = $this->path($fileId .".txt");
        if(file_put_contents($filePath, $captchaText) === false){
            throw new CaptchaException("failed save file to storage");
        }
    }

    public function destroy(){
        $filenames = File::files($this->path());
        $filenamesOnly = array_map('basename', $filenames);

        foreach($filenamesOnly as $file){
            $expired = (int) preg_replace('/[^0-9]/', '', $file);
            if(Carbon::now()->timestamp > $expired){
                unlink($this->path($file));
            }
        }
    }


    public function check($id, $input)
    {
        $captcha = file_get_contents($this->path($id.".txt"));
        return strtoupper($input) === strtoupper($captcha);
    }
}

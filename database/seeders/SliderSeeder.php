<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert default service categories
        DB::table('home_sliders')->insert([
            [
                'image' => 'slider-image-03.jpg',
                'main_title' => 'Internationally Accredited Life Support Courses',
                'pre_title' => 'Department of Health',
                // 'post_title' => 'We believe that with the right guidance, support, and mindset, you can accomplish anything you set your',
                'button_text' => 'Know More',
                'button_link' => '#',
                'status' => 'active',
            ],
            [
                'image' => 'slider-image-01.jpg',
                'main_title' => 'CME & CPD Approved Medical Training',
                'pre_title' => 'Department of Health',
                // 'post_title' => 'We believe that with the right guidance, support, and mindset, you can accomplish anything you set your',
                'button_text' => 'Know More',
                'button_link' => '#',
                'status' => 'active',
            ],
            [
                'image' => 'slider-image-02.jpg',
                'main_title' => 'Exam preparation Study Materials',
                'pre_title' => 'Department of Health',
                // 'post_title' => 'We believe that with the right guidance, support, and mindset, you can accomplish anything you set your',
                'button_text' => 'Know More',
                'button_link' => '#',
                'status' => 'active',
            ],

            [
                'image' => 'slider-image-04.jpg',
                'main_title' => 'Exam preparation Study Materials',
                'pre_title' => 'Department of Health',
                // 'post_title' => 'We believe that with the right guidance, support, and mindset, you can accomplish anything you set your',
                'button_text' => 'Know More',
                'button_link' => '#',
                'status' => 'active',
            ],

            [
                'image' => 'slider-image-05.jpg',
                'main_title' => 'Exam preparation Study Materials',
                'pre_title' => 'Department of Health',
                // 'post_title' => 'We believe that with the right guidance, support, and mindset, you can accomplish anything you set your',
                'button_text' => 'Know More',
                'button_link' => '#',
                'status' => 'active',
            ],
        ]);
    }
}

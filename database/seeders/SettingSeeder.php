<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Basic AI Settings
        Setting::firstOrCreate(['key' => 'ai.enabled'], ['value' => '0']);
        Setting::firstOrCreate(['key' => 'ai.provider'], ['value' => 'openai']);
        
        // OpenAI Settings
        Setting::firstOrCreate(['key' => 'openai.model'], ['value' => 'gpt-4o-mini']);
        Setting::firstOrCreate(['key' => 'openai.temperature'], ['value' => '0.2']);
        Setting::firstOrCreate(['key' => 'openai.max_tokens'], ['value' => '2000']);
        
        // DeepSeek Settings
        Setting::firstOrCreate(['key' => 'deepseek.model'], ['value' => 'deepseek-chat']);
        Setting::firstOrCreate(['key' => 'deepseek.temperature'], ['value' => '0.2']);
        Setting::firstOrCreate(['key' => 'deepseek.max_tokens'], ['value' => '2000']);
        
        // Gemini Settings
        Setting::firstOrCreate(['key' => 'gemini.model'], ['value' => 'gemini-1.5-flash']);
        Setting::firstOrCreate(['key' => 'gemini.temperature'], ['value' => '0.2']);
        Setting::firstOrCreate(['key' => 'gemini.max_tokens'], ['value' => '2000']);
        
        // AI Prompt Customization
        Setting::firstOrCreate(['key' => 'ai.system_prompt'], ['value' => 'You are a Harvard-educated PhD business strategist with 20+ years of experience in retail, finance, and operational excellence. You have zero tolerance for waste and vague thinking. Your mission is to maximize profit, reduce unnecessary expenses, and grow capital through data-driven strategy. Rules: - Respond in clear, professional **Kiswahili** (you may mix light English where helpful). - Never guess; rely only on the data provided. - Be ruthless with underperforming products, and double down on profitable ones. - Output must be practical, prioritized, and directly actionable.']);
        
        Setting::firstOrCreate(['key' => 'ai.language'], ['value' => 'swahili']);
        Setting::firstOrCreate(['key' => 'ai.response_style'], ['value' => 'professional']);
        Setting::firstOrCreate(['key' => 'ai.include_charts'], ['value' => '1']);
        Setting::firstOrCreate(['key' => 'ai.auto_analyze'], ['value' => '1']);
    }
}

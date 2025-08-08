<footer class="w-full rounded-2xl bg-background-secondary/80 backdrop-blur-md border border-neutral/50 mt-8">
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex flex-col space-y-4 max-w-md">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-xl border border-neutral/20">
                        <x-logo class="h-4 w-auto" />
                    </div>
                    <h3 class="text-lg font-bold text-base">{{ config('app.name') }}</h3>
                </div>

                <div class="prose prose-sm text-base/70 prose-headings:text-base prose-p:text-base/60 prose-a:text-primary hover:prose-a:text-primary/80 prose-a:no-underline hover:prose-a:underline">
                    {!! Str::markdown(theme('footer_text', 'Welcome to Paymenter'), [
                        'allow_unsafe_links' => false,
                    ]) !!}
                </div>
                <div class="text-xs text-base/50">
                    {{ __('© :year :app_name. | All rights reserved.', ['year' => date('Y'), 'app_name' => config('app.name')]) }}
                </div>
                <span style="display:none;">Darren_14</span>
            </div>

            <div class="flex flex-col lg:items-end gap-4">
                 
                <div class="flex flex-wrap gap-4 text-sm">
                    @if (theme('privacy_policy_url'))
                        <a href="{{ theme('privacy_policy_url') }}" class="text-base/60 hover:text-secondary transition-colors duration-200">{{ __('Privacy Policy') }}</a>
                    @endif
                    @if (theme('terms_of_service_url'))
                        <a href="{{ theme('terms_of_service_url') }}" class="text-base/60 hover:text-secondary transition-colors duration-200">{{ __('Terms of Service') }}</a>
                    @endif
                    @if (theme('support_url'))
                        <a href="{{ theme('support_url') }}" class="text-base/60 hover:text-secondary transition-colors duration-200">{{ __('Support') }}</a>
                    @endif
                </div>

                <div class="flex gap-3">
                    @if (theme('instagram_url'))
                        <a href="{{ theme('instagram_url') }}" class="p-2 bg-bg-secondary/50 hover:bg-bg-secondary rounded-lg border border-neutral/20 hover:border-neutral/30 transition-all duration-200 group">
                            <x-ri-instagram-fill class="size-4 text-base/60 group-hover:text-secondary" />
                        </a>
                    @endif
                    @if (theme('youtube_url'))
                        <a href="{{ theme('youtube_url') }}" class="p-2 bg-bg-secondary/50 hover:bg-bg-secondary rounded-lg border border-neutral/20 hover:border-neutral/30 transition-all duration-200 group">
                            <x-ri-youtube-fill class="size-4 text-base/60 group-hover:text-secondary" />
                        </a>
                    @endif
                    @if (theme('tiktok_url'))
                        <a href="{{ theme('tiktok_url') }}" class="p-2 bg-bg-secondary/50 hover:bg-bg-secondary rounded-lg border border-neutral/20 hover:border-neutral/30 transition-all duration-200 group">
                            <x-ri-tiktok-fill class="size-4 text-base/60 group-hover:text-secondary" />
                        </a>
                    @endif
                    @if (theme('github_url'))
                        <a href="{{ theme('github_url') }}" class="p-2 bg-bg-secondary/50 hover:bg-bg-secondary rounded-lg border border-neutral/20 hover:border-neutral/30 transition-all duration-200 group">
                            <x-ri-github-fill class="size-4 text-base/60 group-hover:text-secondary" />
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
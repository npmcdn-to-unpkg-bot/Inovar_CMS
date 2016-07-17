<?php namespace System\Console;


use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Cms\Classes\Theme;




class PageCreate extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'page:create';

    /**
     * The console command description.
     */
    protected $description = 'Create a new page.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $themeName = $this->ask('What is the name of the theme?');
        $basepath = themes_path().'/'.$themeName;
        if (Theme::exists($themeName)) {
            $pageName = $this->ask('Theme "'.$themeName.'" find! What is the name of the new page?');
            if($this->confirm('The new page has this name: "'.$pageName.'". Are you sure?')){
                //var theme dir
                $themeAssets                   = $basepath.'/assets';
                $assetsIMAGES                  = $themeAssets.'/images';
                $assetsIMAGESpage              = $assetsIMAGES.'/'.$pageName;
                $assetsJS                      = $themeAssets.'/js';
                $assetsJSdev                   = $assetsJS.'/dev';
                $assetsJSdevPages              = $assetsJSdev.'/pages';
                $assetsLESS                    = $themeAssets.'/less';
                $assetsLESSPages               = $assetsLESS.'/pages';
                $assetsJADE                    = $themeAssets.'/jade';
                $assetsJADEpages               = $assetsJADE.'/pages';
                $assetsJADEpartials            = $assetsJADE.'/partials';
                $assetsJADEpagePartials        = $assetsJADEpartials.'/'.$pageName;

                //var resources dir
                $sourcePath = base_path().'/resources/template';
                $sourceAssetsPath = $sourcePath.'/assets';
                $sourceJadePath = $sourceAssetsPath.'/jade';
                $sourcePagesPath = $sourceJadePath.'/pages';
                $sourceLessPath = $sourceAssetsPath.'/less';
                $sourceLessPagPath = $sourceLessPath.'/pages';
                $sourceJsPath = $sourceAssetsPath.'/js';
                $sourceJsDevPath = $sourceJsPath.'/dev';
                $sourceJsPagPath = $sourceJsDevPath.'/pages';

                // creating new dir
                File::makeDirectory($assetsJADEpagePartials, 0777 );
                if(File::isDirectory($assetsJADEpagePartials)){
                    $this->info('(1)_'.$assetsJADEpagePartials.' created');
                }else{
                    $this->error('(1)_'.$assetsJADEpagePartials.' fail');
                }


                $sourceFILES = [
                    $sourceJadeFileMarkup = $sourcePagesPath.'/_markup.jade',
                    $sourceJadeFileIndex = $sourcePagesPath.'/index.jade',
                    $sourceLessFile = $sourceLessPagPath.'/page.less',
                    $sourceJsFile = $sourceJsPagPath.'/page.js',
                ];
                $destFILES = [
                    $sourceJadeFileMarkup = $assetsJADEpagePartials.'/index.jade',
                    $destJadeFileIndex = $assetsJADEpages.'/'.$pageName.'.jade',
                    $destLessFile = $assetsLESSPages.'/'.$pageName.'.less',
                    $destJsFile = $assetsJSdevPages.'/'.$pageName.'.js',
                ];

                // coping files
                for ($i = 0; $i < count($sourceFILES); ++$i) {
                    File::copy($sourceFILES[$i], $destFILES[$i]);
                    if(File::exists($destFILES[$i])){
                        $this->info('(2)_'.$destFILES[$i].' created');
                    }else{
                        $this->error('(2)_'.$destFILES[$i].' fail');
                    }
                }

                $this->info('**New page "'.$pageName.'" created**');
            }else{
                $this->error('Page creation failed');
            }
        }else{
            $this->error('This theme ('.$themeName.') not exists.');
        }
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}

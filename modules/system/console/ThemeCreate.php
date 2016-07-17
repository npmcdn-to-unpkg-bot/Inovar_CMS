<?php namespace System\Console;


use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Cms\Classes\Theme;




class ThemeCreate extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'theme:create';

    /**
     * The console command description.
     */
    protected $description = 'Create a new theme.';

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
        $themeName = $this->ask('What is the name of the new theme?');

        if (Theme::exists($themeName)) {
            $themeName = $themeName.'_new';
        }
        if($this->confirm('The new theme has this name: "'.$themeName.'". Are you sure?')){


            // var root path
            $basepath = themes_path().'/'.$themeName;

            //create theme dir
            File::makeDirectory($basepath, 0777 );

            // var theme dir
            $mainDIR = [
                $themeAssets = $basepath.'/assets',
                $themeContent = $basepath.'/content',
                $themeLayouts = $basepath.'/layouts',
                $themePages = $basepath.'/pages',
                $themePartials = $basepath.'/partials',
            ];

            foreach($mainDIR as $mainDIRitem){
                // create theme dir
                File::makeDirectory($mainDIRitem, 0777 );
                if(File::isDirectory($mainDIRitem)){
                    $this->info('(1)_'.$mainDIRitem.' created');
                }else{
                    $this->error('(1)_'.$mainDIRitem.' fail');
                }
            }


            //create assets dir structure
            $assetDIRstructure = [
                $assetsIMAGES                  = $themeAssets.'/images',
                $assetsIMAGESlayout            = $assetsIMAGES.'/layout',
                $assetsCSS                     = $themeAssets.'/css',
                $assetsJS                      = $themeAssets.'/js',
                $assetsJSdev                   = $assetsJS.'/dev',
                $assetsJSdevComponents         = $assetsJSdev.'/components',
                $assetsJSdevPages              = $assetsJSdev.'/pages',
                $assetsJSprod                  = $assetsJS.'/prod',
                $assetsLESS                    = $themeAssets.'/less',
                $assetsLESSComponents          = $assetsLESS.'/components',
                $assetsLESSPages               = $assetsLESS.'/pages',
                $assetsJADE                    = $themeAssets.'/jade',
                $assetsJADEcontent             = $assetsJADE.'/content',
                $assetsJADElayouts             = $assetsJADE.'/layouts',
                $assetsJADEpages               = $assetsJADE.'/pages',
                $assetsJADEpartials            = $assetsJADE.'/partials',
                $assetsVendor                  = $themeAssets.'/vendor',
            ];

            // create assets dir
            foreach($assetDIRstructure as $assetDIR){
                File::makeDirectory ($assetDIR, 0777 );
                if(File::isDirectory($assetDIR)){
                    $this->info('(2)__'.$assetDIR.' created');
                }else{
                    $this->error('(2)__'.$assetDIR.' fail');
                }
            }

            // essential source files var path
            $sourcePath = base_path().'/resources/template';
            $sourceAssetsPath = $sourcePath.'/assets';
            $sourceJadePath = $sourceAssetsPath.'/jade';
            $sourceLayoutsPath = $sourceJadePath.'/layouts';
            $sourceLessPath = $sourceAssetsPath.'/less';
            $sourceLessCompPath = $sourceLessPath.'/components';
            $sourceJsPath = $sourceAssetsPath.'/js';
            $sourceJsDevPath = $sourceJsPath.'/dev';
            $sourceJsCompPath = $sourceJsDevPath.'/components';

            $sourceFILES = [
                $sourceInfoBower = $sourcePath.'/.bowerrc',
                $sourceBower = $sourcePath.'/bower.json',
                $sourceInfoGulp = $sourcePath.'/package.json',
                $sourceGulp = $sourcePath.'/gulpfile.js',
                $sourceInfoTheme = $sourcePath.'/theme.yaml',
                $sourceLayoutsFile = $sourceLayoutsPath.'/default.jade',
                $sourceLayoutMixin = $sourceLayoutsPath.'/mixin.jade',
                $sourceLessFile = $sourceLessCompPath.'/layout.less',
                $sourceJsFile = $sourceJsCompPath.'/layout.js',
            ];
            $destFILES = [
                $destInfoBower = $basepath.'/.bowerrc',
                $destBower = $basepath.'/bower.json',
                $destInfoGulp = $basepath.'/package.json',
                $destGulp = $basepath.'/gulpfile.js',
                $destInfoTheme = $basepath.'/theme.yaml',
                $destLayoutsFile = $assetsJADElayouts.'/default.jade',
                $destLayoutMixin = $assetsJADElayouts.'/mixin.jade',
                $destLessFile = $assetsLESSComponents.'/layouts.less',
                $destJsFile = $assetsJSdevComponents.'/layout.js',
            ];

            for ($i = 0; $i < count($sourceFILES); ++$i) {
                File::copy($sourceFILES[$i], $destFILES[$i]);
                if(File::exists($destFILES[$i])){
                    $this->info('(3)___'.$destFILES[$i].' created');
                }else{
                    $this->error('(3)___'.$destFILES[$i].' fail');
                }
            }

            if(Theme::exists($themeName)){
                return $this->info('**New theme "'.$themeName.'" created.**');
            }
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

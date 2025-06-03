<?php
spl_autoload_register(function ($className) {
	$classPath = str_replace("_", "/", subject:$className);
    // echo $classPath;
    @include getcwd().'/lib/psr/log/'.$classPath . '.php';
});
// class PayPal_Autoload
// {
//     private static $registeredLoaders = array();
//     private $vendorDir;
//     private $prefixDirsPsr4 = array();
//     private $prefixLengthsPsr4 = array();
//     private $fallbackDirsPsr4 = array();
//     private $prefixesPsr0 = array();
//     private $fallbackDirsPsr0 = array();
//     private $useIncludePath = false;
//     private $apcuPrefix;
//     private static $includeFile;
//     private $classMap = array();
//     private $missingClasses = array();
//     private $classMapAuthoritative = false;
//     public function __construct($vendorDir = null)
//     {
//         // print("hello appu fetch");
//         $this->vendorDir = $vendorDir;
//         self::initializeIncludeClosure();
//     }
//     public function setClassMapAuthoritative($classMapAuthoritative)
//     {
//         $this->classMapAuthoritative = (bool) $classMapAuthoritative;
//     }
//     public function getClassMap()
//     {
//         return $this->classMap;
//     }
//     public function addClassMap(array $classMap)
//     {
//         $this->classMap = array_merge($this->classMap, $classMap);
//     }
//     /**
//      * Should class lookup fail if not found in the current class map?
//      * @return bool
//      */
//     public function isClassMapAuthoritative()
//     {
//         return $this->classMapAuthoritative;
//     }
//     public function register($prepend = false)
//     {
//         spl_autoload_register(array($this, 'loadClass'), true, $prepend);
//         if ($this->vendorDir === null) {
//             return;
//         }
//         if ($prepend) {
//             self::$registeredLoaders = array($this->vendorDir => $this) + self::$registeredLoaders;
//         } else {
//             self::$registeredLoaders[$this->vendorDir] = $this;
//         }
//     }
//     public static function getRegisteredLoaders()
//     {
//         return self::$registeredLoaders;
//     }
//     private static function initializeIncludeClosure()
//     {
//         if (self::$includeFile !== null) {
//             return;
//         }
//         /**
//          * Scope isolated include.
//          *
//          * Prevents access to $this/self from included files.
//          *
//          * @param  string $file
//          * @return void
//          */
//         self::$includeFile = \Closure::bind(static function ($file) {
//             include $file;
//         }, null, null);
//     }
//     public function loadClass($class)
//     {
//         if ($file = $this->findFile($class)) {
//             $includeFile = self::$includeFile;
//             $includeFile($file);
//             return true;
//         }
//         return null;
//     }
//     public function findFile($class)
//     {
//         // Class map lookup
//         if (isset($this->classMap[$class])) {
//             return $this->classMap[$class];
//         }
//         if ($this->classMapAuthoritative || isset($this->missingClasses[$class])) {
//             return false;
//         }
//         if (null !== $this->apcuPrefix) {
//             $file = apcu_fetch($this->apcuPrefix . $class, $hit);
//             if ($hit) {
//                 return $file;
//             }
//         }
//         $file = $this->findFileWithExtension($class, '.php');
//         // Search for Hack files if running on HHVM
//         if (false === $file && defined('HHVM_VERSION')) {
//             $file = $this->findFileWithExtension($class, '.hh');
//         }
//         if (null !== $this->apcuPrefix) {
//             apcu_add($this->apcuPrefix . $class, $file);
//         }
//         if (false === $file) {
//             $this->missingClasses[$class] = true;
//         }
//         return $file;
//     }
//     public function setApcuPrefix($apcuPrefix)
//     {
//         $enabled = ini_get('apc.enabled');
//         $this->apcuPrefix = (function_exists('apcu_fetch') && ($enabled === false || filter_var($enabled, FILTER_VALIDATE_BOOLEAN)))
//             ? $apcuPrefix
//             : null;
//     }
//     private function findFileWithExtension($class, $ext)
//     {
//         // PSR-4 lookup
//         $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . $ext;
//         $first = $class[0] ?? '';
//         if (isset($this->prefixLengthsPsr4[$first])) {
//             $subPath = $class;
//             while (false !== $lastPos = strrpos($subPath, '\\')) {
//                 $subPath = substr($subPath, 0, $lastPos);
//                 $search = $subPath . '\\';
//                 if (isset($this->prefixDirsPsr4[$search])) {
//                     $pathEnd = DIRECTORY_SEPARATOR . substr($logicalPathPsr4, $lastPos + 1);
//                     foreach ($this->prefixDirsPsr4[$search] as $dir) {
//                         if (file_exists($file = $dir . $pathEnd)) {
//                             return $file;
//                         }
//                     }
//                 }
//             }
//         }
//         // PSR-4 fallback dirs
//         foreach ($this->fallbackDirsPsr4 as $dir) {
//             if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr4)) {
//                 return $file;
//             }
//         }
//         // PSR-0 lookup
//         if (false !== $pos = strrpos($class, '\\')) {
//             $logicalPathPsr0 = substr($logicalPathPsr4, 0, $pos + 1)
//                 . strtr(substr($logicalPathPsr4, $pos + 1), '_', DIRECTORY_SEPARATOR);
//         } else {
//             $logicalPathPsr0 = strtr($class, '_', DIRECTORY_SEPARATOR) . $ext;
//         }
//         if (isset($this->prefixesPsr0[$first])) {
//             foreach ($this->prefixesPsr0[$first] as $prefix => $dirs) {
//                 if (strpos($class, $prefix) === 0) {
//                     foreach ($dirs as $dir) {
//                         if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr0)) {
//                             return $file;
//                         }
//                     }
//                 }
//             }
//         }
//         // PSR-0 fallback dirs
//         foreach ($this->fallbackDirsPsr0 as $dir) {
//             if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr0)) {
//                 return $file;
//             }
//         }
//         // PSR-0 include paths
//         if ($this->useIncludePath && $file = stream_resolve_include_path($logicalPathPsr0)) {
//             return $file;
//         }
//         return false;
//     }
// }
 
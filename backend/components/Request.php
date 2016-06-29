<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\components;

use Yii;
use yii\base\InvalidConfigException;

use common\components\helpers\Util;

/**
 * The web Request class represents an HTTP request
 *
 * It encapsulates the $_SERVER variable and resolves its inconsistency among different Web servers.
 * Also it provides an interface to retrieve request parameters from $_POST, $_GET, $_COOKIES and REST
 * parameters sent via other HTTP methods like PUT or DELETE.
 *
 * Request is configured as an application component in [[\yii\web\Application]] by default.
 * You can access that instance via `Yii::$app->request`.
 *
 * @property string $absoluteUrl The currently requested absolute URL. This property is read-only.
 * @property array $acceptableContentTypes The content types ordered by the quality score. Types with the
 * highest scores will be returned first. The array keys are the content types, while the array values are the
 * corresponding quality score and other parameters as given in the header.
 * @property array $acceptableLanguages The languages ordered by the preference level. The first element
 * represents the most preferred language.
 * @property string $authPassword The password sent via HTTP authentication, null if the password is not
 * given. This property is read-only.
 * @property string $authUser The username sent via HTTP authentication, null if the username is not given.
 * This property is read-only.
 * @property string $baseUrl The relative URL for the application.
 * @property array $bodyParams The request parameters given in the request body.
 * @property string $contentType Request content-type. Null is returned if this information is not available.
 * This property is read-only.
 * @property CookieCollection $cookies The cookie collection. This property is read-only.
 * @property string $csrfToken The token used to perform CSRF validation. This property is read-only.
 * @property string $csrfTokenFromHeader The CSRF token sent via [[CSRF_HEADER]] by browser. Null is returned
 * if no such header is sent. This property is read-only.
 * @property array $eTags The entity tags. This property is read-only.
 * @property HeaderCollection $headers The header collection. This property is read-only.
 * @property string $hostInfo Schema and hostname part (with port number if needed) of the request URL (e.g.
 * `http://www.yiiframework.com`).
 * @property boolean $isAjax Whether this is an AJAX (XMLHttpRequest) request. This property is read-only.
 * @property boolean $isDelete Whether this is a DELETE request. This property is read-only.
 * @property boolean $isFlash Whether this is an Adobe Flash or Adobe Flex request. This property is
 * read-only.
 * @property boolean $isGet Whether this is a GET request. This property is read-only.
 * @property boolean $isHead Whether this is a HEAD request. This property is read-only.
 * @property boolean $isOptions Whether this is a OPTIONS request. This property is read-only.
 * @property boolean $isPatch Whether this is a PATCH request. This property is read-only.
 * @property boolean $isPjax Whether this is a PJAX request. This property is read-only.
 * @property boolean $isPost Whether this is a POST request. This property is read-only.
 * @property boolean $isPut Whether this is a PUT request. This property is read-only.
 * @property boolean $isSecureConnection If the request is sent via secure channel (https). This property is
 * read-only.
 * @property string $method Request method, such as GET, POST, HEAD, PUT, PATCH, DELETE. The value returned is
 * turned into upper case. This property is read-only.
 * @property string $pathInfo Part of the request URL that is after the entry script and before the question
 * mark. Note, the returned path info is already URL-decoded.
 * @property integer $port Port number for insecure requests.
 * @property array $queryParams The request GET parameter values.
 * @property string $queryString Part of the request URL that is after the question mark. This property is
 * read-only.
 * @property string $rawBody The request body. This property is read-only.
 * @property string $referrer URL referrer, null if not present. This property is read-only.
 * @property string $scriptFile The entry script file path.
 * @property string $scriptUrl The relative URL of the entry script.
 * @property integer $securePort Port number for secure requests.
 * @property string $serverName Server name. This property is read-only.
 * @property integer $serverPort Server port number. This property is read-only.
 * @property string $url The currently requested relative URL. Note that the URI returned is URL-encoded.
 * @property string $userAgent User agent, null if not present. This property is read-only.
 * @property string $userHost User host name, null if cannot be determined. This property is read-only.
 * @property string $userIP User IP address. Null is returned if the user IP address cannot be detected. This
 * property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Request extends \yii\web\Request
{
    
    private $_queryParams;
    
    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     * @throws NotFoundHttpException if the request cannot be resolved.
     */
    public function resolve()
    {
        $result = Yii::$app->getUrlManager()->parseRequest($this);
        //pr($result); pr($this->getQueryParams()); //die();        
      
        if ($result !== false) 
        {
            list ($route, $params) = $result;
            if ($this->_queryParams === null) {
                $_GET = $params + $_GET; // preserve numeric keys
            } else {
                $this->_queryParams = $params + $this->_queryParams;
            }
            // Start - Ajay Giri
            $params = $this->getQueryParams();
            //pr($params); die();
            
            $allow = true;
            if(Yii::$app->request->isAjax)
            {
               $allow = false; 
               if(isset($params['q']))
               {
                   $allow = true;   
               }
            }
            
            if(count($params) > 0 && $allow)
            {                
               $q = [];
               if(isset($params['q']))
               {    
                    //echo $params['q'] . '<br>';
                    $params['q'] = str_replace(' ', '+', $params['q']);
                    //echo $params['q'];
                    //$params['q'] = urlencode($params['q']);
                    $qString = Util::Decrypt($params['q']);
                    //$qString = urldecode($qString);
                    parse_str($qString, $q);    
                    
                    //pr($q);
                    //echo $route;
                    if(isset($q['key']) && isset($q['route']) && $q['key'] == '**consumer**now**' && $q['route'] == $route)
                    {                
                       $this->setQueryParams($q);
                    }
                    else
                    {
                       $q = [];    
                    }
               }                            
               //pr($this->getQueryParams()); die();
               return [$route, $q];                
            }            
            else
            {
               return [$route, $params];  
            }
            // End
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
    
}
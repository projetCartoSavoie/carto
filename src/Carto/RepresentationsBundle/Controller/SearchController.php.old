<?php

namespace Carto\RepresentationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
	public function executeSearch(sfWebRequest $request)
	{
		if (!$query = $request->getParameter('query'))
		{
			return $this->forward('job', 'index');
		}

		$this->jobs = Doctrine::getTable('JobeetJob')->getForLuceneQuery($query);

		if ($request->isXmlHttpRequest())
		{
			if ('*' == $query || !$this->jobs)
			{
				return $this->renderText('No results.');
			}
			else
			{
				return $this->renderPartial('job/list', array('jobs' => $this->jobs));
			}
		}
	}
}

<?php


namespace App\Controller;


use App\Entity\Panel;
use App\Entity\Station;
use App\Form\Type\SearchFilterType;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Type\StationType;
use App\Repository\StationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route ("/station")
 *@IsGranted("ROLE_ADMIN")
 */

class StationController extends AbstractController
{

    /**
     * @Route("/listStations", name="station_list", methods={"GET"})
     */
    public function list( Request $request,PaginatorInterface $paginator)
    {
        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Station::class);
        $items = $repository->findAll();
        $stations = $paginator->paginate(
            $items,
            $request->query->getInt('page', 1),
            10
        );
        $repositoryPanel = $this->getDoctrine()->getRepository(Panel::class);
        $itemsPanel = $repositoryPanel->findAll();
        return $this->render('/Stations/ListStations.html.twig',['stations'=> $stations, 'search'=> $searchForm->createView(), 'panels'=>$itemsPanel]);
    }

    /**
     * @Route("/{id}", name="station_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("station", class="App:Station")
     */
    public function station($station)
    {
        $repositoryPanel = $this->getDoctrine()->getRepository(Panel::class);
        $itemsPanel = $repositoryPanel->findAll();
        return $this->render('Stations/DetailsStation.html.twig', ['station'=> $station, 'panels'=>$itemsPanel] );
    }

    /**
     * @Route("/list/{gouvernorat}", name="station_by_gouvernorat", methods={"GET"})
     * @ParamConverter("station", class="App:Station", options={"mapping": {"gouvernorat": "gouvernorat"}})
     */
    public function stationByGouvernorat($station)
    {
        return $this->json($station);
//            $this->getDoctrine()->getRepository(Station::class)->findOneBy(['adresse' => $adresse])
    }

    /**
     * @Route("/add", name="station_add", methods={"POST", "GET"})
     */
    public function add(Request $request)
    {
        $station = new Station();
        $form = $this->createForm(StationType::class,$station);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user=$this->getUser();
            $station->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($station);
            $entityManager->flush();
            return $this->redirectToRoute('station_list');
        }
        return $this->render('/Stations/AddStation.html.twig', array(
            'format' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="station_delete", methods={"GET", "DELETE"})
     */
    public function delete(Station $station)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($station);
        $em->flush();
        return $this->redirectToRoute('station_list');
    }

    /**
     * @Route("/edit/{id}", name="station_edit",methods={"GET","POST"})
     * @ParamConverter("station", class="App:Station")
     */
    public function editPanel(Request $request, $station)
    {
        $form = $this->createForm(StationType::class,$station);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'edit successfully!');
            return $this->redirectToRoute('station_list');
        }
        return $this->render('Stations/EditStation.html.twig', [
            'format' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="search_station")
     */
    public function recherche(Request $request ,StationRepository $repository) {
        $query = $request->request->get('search_filter')['search'];
        if($query){
            $stations= $repository->findAllQueryBuilder($query);
            $repositoryPanel = $this->getDoctrine()->getRepository(Panel::class);
            $itemsPanel = $repositoryPanel->findAll();
        }
        return $this->render('Stations/SearchStation.html.twig', ['stations' => $stations, 'panels'=>$itemsPanel ]);
    }

}
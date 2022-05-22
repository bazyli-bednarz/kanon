<?php

namespace App\DataFixtures;

use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
use App\Entity\User;
use App\Repository\ComposerRepository;
use App\Service\CanonServiceInterface;
use App\Service\ComposerServiceInterface;
use App\Service\ScaleServiceInterface;
use App\Service\TagServiceInterface;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PieceFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    private ComposerServiceInterface $composerService;

    private ScaleServiceInterface $scaleService;

    private TagServiceInterface $tagService;

    private CanonServiceInterface $canonService;

    public function __construct(ComposerServiceInterface $composerService, ScaleServiceInterface $scaleService, TagServiceInterface $tagService, CanonServiceInterface $canonService)
    {
        $this->composerService = $composerService;
        $this->scaleService = $scaleService;
        $this->tagService = $tagService;
        $this->canonService = $canonService;
    }

    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }


        $samplePieces = [
            ['Preludium Deszczowe', 'Jedno z najpiękniejszych preludiów Chopina.', 1839, 'bJmO1g1jlKQ?t=1', 'fryderyk-chopin', 'scale-d-flat-major', ['preludium', 'fortepian'], ['twórczosc-fryderyka-chopina', 'kanon-koncowy']],
            ['Koncert fortepianowy f-moll', 'Jest to jeden z dwóch koncertów Chopina. Wbrew numeracji opusowej to właśnie II Koncert fortepianowy f-moll op. 21 napisany został jako pierwszy.', 1829, 'rawF-OsPE70?t=240', 'fryderyk-chopin', 'scale-f-minor', ['koncert', 'fortepian'], ['twórczosc-fryderyka-chopina', 'kanon-koncowy']],
            ['Etiuda Rewolucyjna', 'Dzieło powstało, kiedy kompozytor dowiedział się o upadku powstania listopadowego.', 1831, 'X0UJEx9S1bU?t=3', 'fryderyk-chopin', 'scale-c-minor', ['etiuda', 'fortepian'], ['twórczosc-fryderyka-chopina', 'kanon-koncowy']],
            ['Nokturn op. 9 no. 2', 'Najbardziej znany nokturn Chopina, jest w metrum 12/8.', 1830, '9E6b3swbnWg?t=1', 'fryderyk-chopin', 'scale-e-flat-major', ['nokturn', 'fortepian'], ['twórczosc-fryderyka-chopina', 'kanon-koncowy']],

            ['Wariacje golbergowskie', 'Utwór napisany dla hrabiego Hermana Karla von Keyserlinga i wykonywany przez Johanna Gottlieba Goldberga, hrabiego i ucznia Bacha, od którego wariacje wzięły swoją nazwę.', 1741, '15ezpwCHtJs?t=0', 'johann-sebastian-bach', 'scale-other', ['klawesyn', 'wariacje', 'fuga', 'fortepian'], ['kanon-koncowy']],
            ['Toccata i fuga d-moll', 'Najbardziej znany utwór organowy Bacha.', 1704, 'SGKfqSJbeAg?t=20', 'johann-sebastian-bach', 'scale-d-minor', ['organy', 'toccata', 'fuga'], ['kanon-koncowy']],

            ['Symfonia no. 45 Pożegnalna', 'Napisana jako aluzja do patrona Haydna, księcia Nicholasa Esterhazego, który zwlekał z zapłatą dla orkiestry. W ostatniej części utworu muzycy opuszczają po kolei scenę, aż w końcu muzyka ucicha i na scenie nie zostaje nikt.', 1772, 'KXctarOxRz8?t=1', 'joseph-haydn', 'scale-f-sharp-minor', ['symfonia'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Sonata D-dur', 'Utwór świetnie ukazuje formę sonatową.', 1780, '0FqCqwaoXVg?t=1', 'joseph-haydn', 'scale-d-major', ['sonata', 'fortepian'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Uwertura koncertowa “Coriolan” op. 62', 'Muzyczna ilustracja dramatu austriackiego poety Heinricha von Collina.', 1807, 'D1PKSoi9lNk?t=7', 'ludwig-van-beethoven', 'scale-c-minor', ['uwertura'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Sonata fortepianowa C-dur op. 53 “Waldsteinowska”', 'Należy do kluczowych dzieł środkowego, "heroicznego" okresu twórczości kompozytora.', 1804, 'TDagcm5Nl4s?t=15', 'ludwig-van-beethoven', 'scale-c-major', ['sonata', 'fortepian'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Sonata Ksieżycowa, cz. 3', 'Jedna z najsłynniejszych sonat w historii muzyki, była zadedykowana ukochanej kompozytora, hrabinie Giuletcie Guicciardi. Została stworzona, kiedy kompozytor zaczął już tracić słuch.', 1801, 'BV7RkEL6oRc?t=4', 'ludwig-van-beethoven', 'scale-c-sharp-minor', ['sonata', 'fortepian'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Requiem d-moll KV 626', 'Jest jednym z największych utworów sakralnych Mozarta, a zarazem jego ostatnią, niedokończoną kompozycją.', 1791, 'O20HzXEX_xU?t=35', 'wolfgang-amadeus-mozart', 'scale-d-minor', ['requiem'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Symfonia Wielka no. 40, KV 550', 'Jedna z dwóch symfonii molowych Mozarta. Jej główny motyw pojawia się w XXI koncercie fortepianowym C-dur.', 1788, 'JTc1mDieQI8?t=5', 'wolfgang-amadeus-mozart', 'scale-g-minor', ['symfonia'], ['klasycyzm-klasa-v', 'kanon-koncowy']],
            ['Symfonia Praska no. 38', 'Jedna z trzech wielkich symfonii Mozarta. Kompozytor napisał ją po pobycie w stolicy Czech.', 1787, 'ot3g41rHFqU?t=1', 'wolfgang-amadeus-mozart', 'scale-d-major', ['symfonia'], ['klasycyzm-klasa-v', 'kanon-koncowy']],

            ['Balet “Byk na dachu“', 'Balet zainspirowany muzyką Brazylii. Utwór przechodzi przez wszystkie 12 tonacji.', 1920, 'VZLfIKYg0Pg?t=1', 'darius-milhaud', 'scale-other', ['balet'], ['utwory-xx-wieku', 'kanon-koncowy']],
            ['Partiels', 'Utwór wykorzystujący technikę spektralizmu - grania przez muzyków szeregu alikwotów dźwięku granego w basie.', 1975, '1v7onrjN6RE', 'gerard-grisey', 'scale-other', ['spektralizm'], ['utwory-xx-wieku', 'kanon-koncowy']],
            ['Święto Wiosny', 'Dzieło to jest wywarło ogromny wpływ na muzykę XX wieku. Tematem przewodnim tego baletu jest pogańskie święto, podczas którego pogrążeni w transie kultyści składają ofiarę z człowieka. Utwór podczas premiery spowodował ogromny skandal.', 1913, 'rP42C-4zL3w?t=2', 'igor-stravinsky', 'scale-other', ['balet', 'modernizm', 'witalizm'], ['utwory-xx-wieku', 'kanon-koncowy']],
            ['Pietruszka', 'Nowatorski balet, opowiadający historię marionetki o imieniu Pietruszka.', 1911, 'jeSC0vtdn3g?t=1', 'igor-stravinsky', 'scale-other', ['balet', 'pietruszka'], ['utwory-xx-wieku', 'kanon-koncowy']],
            ['Quatuor pour la fin du temps', 'Jego przetłumaczona nazwa oznacza Kwartet dla Końca Świata. Jest napisany na klarnet, skrzypce, wiolonczelę i fortepian.', 1941, 'jXxmvsllhCg?t=61', 'olivier-messiaen', 'scale-other', ['kwartet-smyczkowy'], ['utwory-xx-wieku', 'kanon-koncowy']],
            ['Turangalîla-Symphonie', 'Inspiracją dla tej symfonii był mit o Tristanie i Izoldzie. W utworze występuje partia grana na falach Martenota.', 1949, '9r4eeMZBInY?t=39', 'olivier-messiaen', 'scale-other', ['symfonia'], ['utwory-xx-wieku', 'kanon-koncowy']],
        ];

        $this->createMany(count($samplePieces), 'pieces', function (int $i) use ($samplePieces) {
            $piece = new Piece();
            $piece->setName($samplePieces[$i][0]);
            $piece->setDescription($samplePieces[$i][1]);
            $piece->setYear($samplePieces[$i][2]);
            $piece->setLink($samplePieces[$i][3]);
            $piece->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $piece->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $piece->setComposer($this->composerService->findOneBySlug($samplePieces[$i][4]));

            $piece->setScale($this->scaleService->findOneBySlug($samplePieces[$i][5]));

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $piece->setAuthor($author);
            $piece->setEditedBy($author);

            $tagsCount = count($samplePieces[$i][6]);
            if ($tagsCount) {
                for ($j = 1; $j <= $tagsCount; $j++) {
                    $tag = $this->tagService->findOneBySlug($samplePieces[$i][6][$j - 1]);
                    $piece->addTag($tag);
                }
            }

            $canonsCount = count($samplePieces[$i][7]);
            if ($canonsCount) {
                for ($j = 1; $j <= $canonsCount; $j++) {
                    $canon = $this->canonService->findOneBySlug($samplePieces[$i][7][$j - 1]);
                    $piece->addCanon($canon);
                }
            }
            return $piece;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: ComposerFixtures::class, 1: ScaleFixtures:class}
     */
    public function getDependencies(): array
    {
        return [ComposerFixtures::class, ScaleFixtures::class, TagFixtures::class, CanonFixtures::class, UserFixtures::class];
    }


}
